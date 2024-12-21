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


