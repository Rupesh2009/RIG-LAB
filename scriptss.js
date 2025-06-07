document.addEventListener("DOMContentLoaded", function () {
  const submitBtn = document.getElementById("submitBtn");

  // Form submit handler
  document.getElementById("careerForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submit

    const getVal = id => document.getElementById(id).value || "";

    const prompt = `Suggest a career path for a student with the following details... // [Your long prompt remains unchanged]`;

    const apiKey = "AIzaSyA18yz4KYnxdl7cTRSLT51cu7qMUPtOmKQ";
    const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;

    const requestBody = {
      contents: [
        {
          parts: [{ text: prompt }]
        }
      ]
    };

    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(requestBody),
    })
    .then(response => response.json())
    .then(data => {
      const resultText = data?.candidates?.[0]?.content?.parts?.[0]?.text;
      if (resultText) {
        document.getElementById("careerResult").style.display = "block";
        document.getElementById("resultText").textContent = resultText;
        document.getElementById("downloadBtn").style.display = "inline-block";

        // PDF creation
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });

        doc.setFontSize(18);
        doc.setFont("helvetica", "bold");
        doc.text("Suggested Career Path", 105, 20, { align: "center" });

        doc.setFont("helvetica", "normal");
        doc.setFontSize(12);

        let y = 35;
        const splitText = doc.splitTextToSize(resultText, 180);
        splitText.forEach(line => {
          if (y > 270) {
            doc.addPage();
            y = 20;
            addWatermark();
          }
          doc.text(line, 15, y);
          y += 7;
        });

        function addWatermark() {
          doc.setTextColor(200);
          doc.setFontSize(40);
          doc.setFont("helvetica", "bold");
          doc.text("RIG LAB", 105, 150, { align: "center", angle: 45 });
          doc.setTextColor(0);
          doc.setFontSize(12);
        }

        addWatermark();

        document.getElementById("downloadBtn").onclick = () => {
          doc.save("Career_Path_Suggestion.pdf");
        };
      } else {
        console.error("No response text received");
        alert("Something went wrong. Please try again.");
      }
    })
    .catch(error => {
      console.error("Error fetching career path:", error);
      alert("Failed to fetch career suggestion.");
    });
  });
});
