function saveTime(type) {
            let currentTime = new Date().toLocaleTimeString('ja-JP', { hour12: false });

            fetch("/save-time", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")

                },
                body: JSON.stringify({ type: type, time: currentTime })
            })
            .then(response => response.text()) 
            .then(text => {
            console.log("Raw Response:", text); 
            return JSON.parse(text); 
            })
            .then(data => {
                console.log("Success:", data);
                updateButtons(type);
            })
            .catch(error => console.error("Error Response:", error));
        }

        function updateButtons(type) {
    if (type === "punchIn") {
        document.getElementById("work-form__btn").style.display = "none";
        document.getElementById("rest-in__btn").style.display = "block";
        document.getElementById("finish-work__btn").style.display = "block";
    } else if (type === "breakStart") {
        document.getElementById("rest-in__btn").style.display = "none";
        document.getElementById("rest-out__btn").style.display = "block";
        
    } else if (type === "breakEnd") {
        document.getElementById("rest-in__btn").style.display = "block";
        document.getElementById("rest-out__btn").style.display = "none";
    } else if (type === "punchOut") {
        document.getElementById("attendance").style.display = "none";

        messageDiv = document.getElementById("message");
        messageDiv.innerText = "お疲れ様でした。";
        messageDiv.style.display = "block";
    }
}




document.getElementById("work-form__btn").addEventListener("click", function () {
    recordTime("punchIn");
});

document.getElementById("rest-in__btn").addEventListener("click", function () {
    recordTime("breakStart");
});

document.getElementById("rest-out__btn").addEventListener("click", function () {
    recordTime("breakEnd");
});

document.getElementById("finish-work__btn").addEventListener("click", function () {
    recordTime("punchOut");
});



