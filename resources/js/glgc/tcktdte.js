
    const uploadArea = document.querySelector('#uploadArea')
    const dropZoon = document.querySelector('#dropZoon');
    const loadingText = document.querySelector('#loadingText');
    const fileInput = document.querySelector('#ticket');
    const previewImage = document.querySelector('#previewImage');
    const fileDetails = document.querySelector('#fileDetails');
    const uploadedFile = document.querySelector('#uploadedFile');
    const uploadedFileInfo = document.querySelector('#uploadedFileInfo');
    const uploadedFileName = document.querySelector('.uploaded-file__name');
    const uploadedFileCounter = document.querySelector('.uploaded-file__counter');
    const displayInput = document.getElementById('transaction_date_display');
    const hiddenInput = document.getElementById('transaction_date');
    const errorText = document.getElementById('transaction_date_error');
    const imagesTypes = [
        "jpeg",
        "png",
    ];
    dropZoon.addEventListener('dragover', function (event) {
    event.preventDefault();
    dropZoon.classList.add('drop-zoon--over');
    });
    dropZoon.addEventListener('dragleave', function (event) {
    dropZoon.classList.remove('drop-zoon--over');
    });
    dropZoon.addEventListener('drop', function (event) {
    event.preventDefault();
    dropZoon.classList.remove('drop-zoon--over');
    const file = event.dataTransfer.files[0];
    uploadFile(file);
    });
    dropZoon.addEventListener('click', function (event) {
    fileInput.click();
    });
    fileInput.addEventListener('change', function (event) {
    const file = event.target.files[0];
    uploadFile(file);
    });
    function uploadFile(file) {
        const fileReader = new FileReader();
        const fileType = file.type;
        const fileSize = file.size;
        if (fileValidate(fileType, fileSize)) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            dropZoon.classList.add('drop-zoon--Uploaded');
            loadingText.style.display = "block";
            previewImage.style.display = 'none';
            uploadedFile.classList.remove('uploaded-file--open');
            uploadedFileInfo.classList.remove('uploaded-file__info--active');
            fileReader.addEventListener('load', function () {
            setTimeout(function () {
                uploadArea.classList.add('upload-area--open');
                loadingText.style.display = "none";
                previewImage.style.display = 'block';
                fileDetails.classList.add('file-details--open');
                uploadedFile.classList.add('uploaded-file--open');
                uploadedFileInfo.classList.add('uploaded-file__info--active');
            }, 500); 
            previewImage.setAttribute('src', fileReader.result);
            uploadedFileName.innerHTML = file.name;
            progressMove();
            });
            fileReader.readAsDataURL(file);
        } else { 
            this;
        };
    };
    function progressMove() {
        let counter = 0;
        setTimeout(() => {
            let counterIncrease = setInterval(() => {
            if (counter === 100) {
                clearInterval(counterIncrease);
            } else {
                counter = counter + 10;
                uploadedFileCounter.innerHTML = `${counter}%`
            }
            }, 100);
        }, 600);
    };


    function fileValidate(fileType, fileSize) {
        let isImage = imagesTypes.filter((type) => fileType.indexOf(`image/${type}`) !== -1);

        if (isImage.length !== 0) {
            if (fileSize <= 2000000) {
            return true;
            } else {
            return alert('Please Your File Should be 2 Megabytes or Less');
            };
        } else {
            return alert('Please make sure to upload An Image File Type');
        };
    };

    function formatDateInput(value) {
        value = value.replace(/\D/g, '');

        if (value.length > 8) {
            value = value.substring(0, 8);
        }

        let day = value.substring(0, 2);
        let month = value.substring(2, 4);
        let year = value.substring(4, 8);

        if(day > 31) {
            day = '31';
        }
        if(month > 12) {
            month = '12';
        }

        let formatted = day;

        if (month) {
            formatted += '/' + month;
        }

        if (year) {
            formatted += '/' + year;
        }

        return formatted;
    }

    function ddmmyyyyToYmd(value) {
        const parts = value.split('/');

        if (parts.length !== 3) {
            return null;
        }

        const day = parts[0];
        const month = parts[1];
        const year = parts[2];

        if (day.length !== 2 || month.length !== 2 || year.length !== 4) {
            return null;
        }

        const ymd = `${year}-${month}-${day}`;

        const date = new Date(`${ymd}T00:00:00`);

        if (isNaN(date.getTime())) {
            return null;
        }

        const isSameDate =
            date.getFullYear() === Number(year) &&
            date.getMonth() + 1 === Number(month) &&
            date.getDate() === Number(day);

        if (!isSameDate) {
            return null;
        }
        console.log('Valid date:', ymd);
        return ymd;
    }

    function validateDate() {
        const value = displayInput.value;
        const ymd = ddmmyyyyToYmd(value);

        const min = displayInput.dataset.min;
        const max = displayInput.dataset.max;

        errorText.style.display = 'none';
        errorText.textContent = '';
        hiddenInput.value = '';

        if (!ymd) {
            if (value.length === 10) {
                errorText.textContent = 'Ingresa una fecha válida con formato DD/MM/YYYY.';
                errorText.style.display = 'block';
            }

            return;
        }

        if (ymd < min) {
            errorText.textContent = `La fecha no puede ser menor a ${min.split('-').reverse().join('/')}.`;
            errorText.style.display = 'block';
            return;
        }

        if (ymd > max) {
            errorText.textContent = `La fecha no puede ser mayor a ${max.split('-').reverse().join('/')}.`;
            errorText.style.display = 'block';
            return;
        }

        hiddenInput.value = ymd;
    }

    displayInput.addEventListener('input', function () {
        this.value = formatDateInput(this.value);
        validateDate();
    });

    displayInput.addEventListener('blur', validateDate);