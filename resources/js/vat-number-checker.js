document.addEventListener('alpine:init', () => {
    Alpine.data('vatNumberChecker', (config = {}) => ({

        state: 'idle',
        message: '',
        sequence: 0,
        controller: null,

        async check(value) {
            const MIN_VAT_CODE_LENGTH = 3;

            const vatNumber = String(value ?? '').trim();

            if (this.strip(vatNumber).length < MIN_VAT_CODE_LENGTH) {
                this.abortCurrentRequest();
                this.setState('idle');

                return;
            }

            await this.lookup(vatNumber);
        },

        async lookup(vatNumber) {
            this.abortCurrentRequest();

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
            this.clearFields();

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

        abortCurrentRequest() {
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
