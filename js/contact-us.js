  const contactFileInput = document.getElementById('contact-file');
  const fileNameDisplay = document.getElementById('file-name-display');

  if (contactFileInput && fileNameDisplay) {
    contactFileInput.addEventListener('change', function () {
      if (this.files && this.files.length > 0) {
        fileNameDisplay.value = this.files[0].name;
      } else {
        fileNameDisplay.value = '';
      }
    });
  }