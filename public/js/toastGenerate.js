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
    <strong class="mr-auto">${title}</strong>
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