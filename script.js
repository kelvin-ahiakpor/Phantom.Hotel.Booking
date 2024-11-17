// JavaScript function to update image previews
function updateImagePreview() {
    // Get file input elements
    const image1 = document.getElementById('hotelImage1').files[0];
    const image2 = document.getElementById('hotelImage2').files[0];
    const image3 = document.getElementById('hotelImage3').files[0];

    // Create image preview for each file
    if (image1) {
        const reader1 = new FileReader();
        reader1.onload = function(e) {
            document.getElementById('previewImage1').src = e.target.result;
        };
        reader1.readAsDataURL(image1);
    }

    if (image2) {
        const reader2 = new FileReader();
        reader2.onload = function(e) {
            document.getElementById('previewImage2').src = e.target.result;
        };
        reader2.readAsDataURL(image2);
    }

    if (image3) {
        const reader3 = new FileReader();
        reader3.onload = function(e) {
            document.getElementById('previewImage3').src = e.target.result;
        };
        reader3.readAsDataURL(image3);
    }
}
