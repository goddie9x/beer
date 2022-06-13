const AlertTypes = {
    SUCCESS: 'success',
    INFO: 'info',
    WARNING: 'warning',
    DANGER: 'danger'
}
const IconAlertList = {
    success: '<i class="fas fa-check-circle"></i>',
    danger: '<i class="fas fa-exclamation-circle"></i>',
    warning: '<i class="fas fa-exclamation-triangle"></i>',
    info: '<i class="fas fa-info-circle"></i>'
}

function showToast({ type, title, message }, timeout = 6000, container = '.toasts-container') {
    let isClosed = false;
    let toastContainer = document.querySelector(container);
    let toast = document.createElement('div');
    let toastClose = document.createElement('button');
    toastClose.className = 'btn-close position-absolute top-0 end-0 p-2';
    toastClose.addEventListener('click', function(e) {
        toast.remove();
        isClosed = true;
    });
    toast.className = 'alert alert-' + type;
    toast.innerHTML = `<div class="alert-heading">
    <strong class="mr-auto">${IconAlertList[type]} ${ title}</strong>
    </div>
    <div class="alert-body">
    ${message}
    </div>`;
    toast.prepend(toastClose);
    toastContainer.appendChild(toast);
    setTimeout(function() {
        if (!isClosed) {
            toast.remove();
        }
    }, timeout);
}