document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    fetch('https://webhook.site/9e7688e4-6c8c-4697-811f-bde8d0f1c304', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name: name, email: email })
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response from your API
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
