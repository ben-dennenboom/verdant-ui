const url = '/api/vat-number-check';

async function verify(vatNumber) {
    try {
        const response = await fetch(`${url}?vatNumber=${encodeURIComponent(vatNumber)}`);

        if (response.status === 404) {
            return null;
        }

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        return null;
    }
}

function display(data, nameElementId, addressElementId) {
    const fields = {
        [nameElementId]: data.name,
        [addressElementId]: data.address,
    };

    Object.entries(fields).forEach(([id, value]) => {
        const element = document.getElementById(id);

        if (element == null) {
            return;
        }

        element.value = value;
    });
}

function resetField(field) {
    field.value = '';
}

function resetFields(readerId, nameElementId, addressElementId) {
    const [r, n, a] = [
        document.getElementById(readerId),
        document.getElementById(nameElementId),
        document.getElementById(addressElementId),
    ];

    [r, n, a].forEach((field) => {
        if (field === r) return;
        if (field !== null) resetField(field);
    });

    if (r !== null) {
        const [l, v, i] = [
            document.getElementById('loadingIndicator'),
            document.getElementById('validIndicator'),
            document.getElementById('invalidIndicator'),
        ];

        [l, v, i].forEach((status) => {
            if (status === l) {
                status.hidden = false;
            } else if (status !== null) {
                status.hidden = true;
            }
        });
    }
}

async function validate(readerId, nameElementId, addressElementId) {
    resetFields(readerId, nameElementId, addressElementId);

    let vatNumber = null;

    // Get the value
    const element = document.getElementById(readerId);

    if (element) {
        vatNumber = element.value;
    }
    if (vatNumber === null || vatNumber === '') {
        return false;
    }

    // Display the data
    const data = await verify(vatNumber);

    if (data && nameElementId && addressElementId) {
        display(data, nameElementId, addressElementId);
    }

    if (data !== null) {
        const successIndicator = document.getElementById('successIndicator');

        if (successIndicator) {
            successIndicator.hidden = false;
        }
    } else {
        const failureIndicator = document.getElementById('failureIndicator');

        if (failureIndicator) {
            failureIndicator.hidden = false;
        }
    }
}