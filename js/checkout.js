document.getElementById("confirmCheckout").addEventListener("click", function () {
    // Confirm checkout with an AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "checkout.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message);
                // Clear the cart session (this requires server-side handling in production)
                window.location.href = "index.php"; // Redirect to home page
            } else {
                alert("Failed to process the order. Try again.");
            }
        }
    };
    xhr.send();
});
