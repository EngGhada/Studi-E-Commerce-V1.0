
// this code allow the message box to fade out after 5 seconds and then remove itself from the DOM.
document.addEventListener('DOMContentLoaded', () => {
    const messageBox = document.getElementById('message-box');
    if (messageBox) {
        setTimeout(() => {
            messageBox.style.transition = "opacity 1s";
            messageBox.style.opacity = "0";
            setTimeout(() => messageBox.remove(), 1000); // Fully remove after fade-out
        }, 5000); // 5000 milliseconds 
    }
});

