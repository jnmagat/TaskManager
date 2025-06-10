
function toggleVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;

    // Flip type
    const isPassword = input.getAttribute('type') === 'password';
    input.setAttribute('type', isPassword ? 'text' : 'password');

    // Swap icons
    const eyeIcon = btn.querySelector('.eye-icon');
    const eyeOffIcon = btn.querySelector('.eye-off-icon');

    if (isPassword) {
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}

// Expose globally so Blade’s onclick still works
window.toggleVisibility = toggleVisibility;
