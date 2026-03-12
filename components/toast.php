<style>
/* Toast container */
#toast-container {
    position: fixed;
    top: 24px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    gap: 12px;
    z-index: 9999;
    pointer-events: none;
}

.goey-toast {
    background: white;
    color: black;
    border: 1.5px solid black;
    border-radius: 9999px;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    opacity: 0;
}

.dark .goey-toast {
    background: #111;
    color: white;
    border-color: white;
    box-shadow: 0 4px 12px rgba(255,255,255,0.1);
}

@keyframes toast-spring-in {
    0% {
        opacity: 0;
        transform: translateY(-30px) scale(0.9);
    }
    40% {
        opacity: 1;
        transform: translateY(5px) scale(1.02);
    }
    70% {
        transform: translateY(-2px) scale(0.99);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.goey-toast.show {
    animation: toast-spring-in 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}

@keyframes toast-spring-out {
    0% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    100% {
        opacity: 0;
        transform: translateY(-30px) scale(0.9);
    }
}

.goey-toast.hide {
    animation: toast-spring-out 0.4s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards;
}

.toast-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}
.toast-icon.error { color: #e11d48; }
.toast-icon.success { color: #16a34a; }
</style>

<div id="toast-container"></div>

<script>
window.goeyToast = {
    show: function(message, type = 'default') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'goey-toast';
        
        let iconHtml = '';
        if (type === 'error') {
            iconHtml = '<span class="material-icons text-xl toast-icon error">error_outline</span>';
        } else if (type === 'success') {
            iconHtml = '<span class="material-icons text-xl toast-icon success">check_circle_outline</span>';
        } else {
            iconHtml = '<span class="material-icons text-xl toast-icon">info_outline</span>';
        }
        
        toast.innerHTML = iconHtml + '<span>' + message + '</span>';
        container.appendChild(toast);
        
        // Trigger reflow for animation
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });
        
        setTimeout(() => {
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 400); // Wait for hide animation
        }, 4000);
    },
    error: function(msg) { this.show(msg, 'error'); },
    success: function(msg) { this.show(msg, 'success'); },
    info: function(msg) { this.show(msg, 'info'); }
};
</script>