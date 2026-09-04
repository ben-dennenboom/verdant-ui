document.addEventListener('alpine:init', () => {
    Alpine.data('vatNumberChecker', (config = {}) => ({
        /**
         * One of: idle, loading, valid, invalid, error.
         * A single source of truth so exactly one indicator can ever be visible.
         */
        state: 'idle',

        /** Screen-reader announcement mirroring the current state. */
        message: '',

        /** Incremented per lookup so a slow earlier response cannot overwrite a newer one. */
        sequence: 0,

        controller: null,

        /**
         * Returns the in-flight lookup so callers can await it; Alpine ignores it.
         */
        async check(value) {
            const vatNumber = String(value ?? '').trim();

            this.clearFields();

            // Too short to be a country code plus a number, so skip the round trip
            // and leave the field neutral rather than calling it invalid.
            if (this.strip(vatNumber).length < 3) {
                this.abort();
                this.setState('idle');

                return;
            }

            await this.lookup(vatNumber);
        },

        async lookup(vatNumber) {
            this.abort();

            const sequence = ++this.sequence;
            const controller = new AbortController();

            this.controller = controller;

            this.setState('loading');

            let response;

            try {
                response = await fetch(`${config.url}?vatNumber=${encodeURIComponent(vatNumber)}`, {
                    headers: { Accept: 'application/json' },
                    signal: controller.signal,
                });
            } catch (error) {
                // An abort means a newer lookup has taken over and owns the state now.
                if (error.name !== 'AbortError' && sequence === this.sequence) {
                    this.setState('error');
                }

                return;
            }

            if (sequence !== this.sequence) {
                return;
            }

            if (response.status === 404 || response.status === 422) {
                this.setState('invalid');

                return;
            }

            if (!response.ok) {
                this.setState('error');

                return;
            }

            let payload;

            try {
                payload = await response.json();
            } catch (error) {
                this.setState('error');

                return;
            }

            if (sequence !== this.sequence) {
                return;
            }

            this.fill(payload?.data ?? {});
            this.setState('valid');
        },

        fill(data) {
            [
                [config.nameId, data.name],
                [config.addressId, data.address],
            ].forEach(([id, value]) => {
                if (!id || value === null || value === undefined) {
                    return;
                }

                const field = document.getElementById(id);

                if (field !== null) {
                    field.value = value;
                }
            });
        },

        clearFields() {
            [config.nameId, config.addressId].forEach((id) => {
                if (!id) {
                    return;
                }

                const field = document.getElementById(id);

                if (field !== null) {
                    field.value = '';
                }
            });
        },

        abort() {
            if (this.controller !== null) {
                this.controller.abort();
                this.controller = null;
            }
        },

        setState(state) {
            const messages = {
                idle: '',
                loading: 'Checking VAT number',
                valid: 'VAT number verified',
                invalid: 'VAT number not found',
                error: 'Could not verify the VAT number right now',
            };

            this.state = state;
            this.message = messages[state] ?? '';
        },

        strip(value) {
            return value.replace(/[^A-Za-z0-9]/g, '');
        },
    }));
});
