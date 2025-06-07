const API_KEY = "AIzaSyDx_ByAyeTR1PIW7s4fZrqs_lb7yAP1Ts4"; // Replace with actual API key
const chatBox = document.getElementById("chat-box");
const userInput = document.getElementById("user-input");

async function sendMessage() {
    let userText = userInput.value.trim();
    if (!userText) return;

    appendMessage("You", userText, "user-message");
    userInput.value = "";

    // Show typing indicator
    appendMessage("Bot", "Typing...", "bot-message", "typing");

    try {
        const response = await fetch("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" + API_KEY, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                contents: [{ parts: [{ text: userText }] }]
            })
        });

        const data = await response.json();
        const botReply = data.candidates?.[0]?.content?.parts?.[0]?.text || "No response received.";

        // Remove typing indicator
        removeTypingIndicator();

        appendMessage("Bot", botReply, "bot-message");
    } catch (error) {
        removeTypingIndicator();
        appendMessage("Bot", "Error: " + error.message, "bot-message");
    }
}

function appendMessage(sender, message, className, id = "") {
    const msgDiv = document.createElement("div");
    msgDiv.classList.add("chat-message", className);
    if (id) msgDiv.id = id;
    msgDiv.innerHTML = `<strong>${sender}:</strong> ${message}`;
    chatBox.appendChild(msgDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function removeTypingIndicator() {
    const typingIndicator = document.getElementById("typing");
    if (typingIndicator) typingIndicator.remove();
}
