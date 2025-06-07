document.getElementById('check-btn').addEventListener('click', function() {
    const newsUrl = document.getElementById('news-url').value;

    if (newsUrl === "") {
        alert("Please enter a valid news URL.");
        return;
    }

    // Log the URL being sent to the server for debugging
    console.log("Checking news for URL:", newsUrl);

    // Make a request to the PHP server-side endpoint
    fetchFactCheckServer(newsUrl);
});

// Fetch fact-checking data using server-side PHP endpoint (no API key needed here)
async function fetchFactCheckServer(url) {
    const apiUrl = `https://phpstack-1417858-5300518.cloudwaysapps.com/factcheck.php?url=${encodeURIComponent(url)}`;  // Replace with your domain

    try {
        console.log('Sending request to PHP server:', apiUrl);  // Log the request URL
        
        const response = await fetch(apiUrl);

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        console.log('Server Response:', data);  // Log the server response

        const result = document.getElementById('result');
        const factCheckResult = document.getElementById('fact-check-result');

        if (data && data.items && data.items.length > 0) {
            const factCheckData = data.items[0];
            factCheckResult.innerHTML = `
                <strong>Fact Check Source:</strong> ${factCheckData.claimReview[0].publisher.name}<br>
                <strong>Claim Review:</strong> ${factCheckData.claimReview[0].textualBody}<br>
                <strong>Rating:</strong> ${factCheckData.claimReview[0].claimRating.rating}
            `;
        } else {
            factCheckResult.innerHTML = "No fact check available for this news URL.";
        }

        result.classList.remove('hidden');
    } catch (error) {
        console.error("Error fetching data from the server:", error);  // Log any errors that occur during the fetch

        const result = document.getElementById('result');
        const factCheckResult = document.getElementById('fact-check-result');
        
        factCheckResult.innerHTML = `Error fetching data: ${error.message}. Please try again later.`;
        result.classList.remove('hidden');
    }
}
