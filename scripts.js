// scripts.js

function downloadFile(fileName, fileUrl) {
    alert(`Starting download for: ${fileName}`);

    // Create a temporary link element
    const link = document.createElement('a');
    link.href = fileUrl; // Set the URL of the file
    link.download = fileName; // Set the file name for the download
    link.style.display = 'none';

    // Append the link to the body (required for Firefox)
    document.body.appendChild(link);

    // Programmatically click the link to trigger the download
    link.click();

    // Remove the link element after download
    document.body.removeChild(link);

    console.log(`Downloading file: ${fileName}`);
}


// index.html
document.querySelector('.burger-menu').addEventListener('click', () => {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
});

// Showcase section
document.addEventListener("DOMContentLoaded", () => {
    const fadeInElements = document.querySelectorAll('.fade-in');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    fadeInElements.forEach((element) => {
        observer.observe(element);
    });
});

//
// announcements.html
//
// Function to fetch announcements from the JSON file
async function loadAnnouncements() {
    const announcementsList = document.getElementById("announcements-list");
    const noAnnouncementsMessage = document.getElementById("no-announcements-message");

    try {
        const response = await fetch('announcements.json');
        
        // Check if the response is successful
        if (!response.ok) {
            throw new Error('Failed to load announcements');
        }

        const announcementsData = await response.json();

        // If no announcements, show the fallback message
        if (announcementsData.length === 0) {
            noAnnouncementsMessage.style.display = 'block';
        } else {
            noAnnouncementsMessage.style.display = 'none'; // Hide fallback message

            // Add announcements to the page
            announcementsData.forEach(announcement => {
                const announcementDiv = document.createElement("div");
                announcementDiv.classList.add("announcement");
                announcementDiv.innerHTML = `
                    <h3>${announcement.title}</h3>
                    <p class="announcement-date">${announcement.date}</p>
                    <p>${announcement.content}</p>
                `;
                announcementsList.appendChild(announcementDiv);
            });
        }
    } catch (error) {
        console.error('Error loading announcements:', error);
        noAnnouncementsMessage.style.display = 'block'; // Show the "No announcements" message in case of error
    }
}

// Load announcements when the page is loaded
window.onload = loadAnnouncements;
