
document.addEventListener("DOMContentLoaded", () => {
    const datepickerInitDate = document.getElementById('datepickerInitDate');
    const datepickerEndDate = document.getElementById('datepickerEndDate');

    if (datepickerInitDate != undefined && datepickerEndDate != undefined) {
        const datepickerInitInstance = new Datepicker(datepickerInitDate, {
            format: 'yyyy-mm-dd'
        });
        const datepickerEndInstance = new Datepicker(datepickerEndDate, {
            format: 'yyyy-mm-dd'
        });

        let saveDateBtns = document.querySelectorAll('.date-set');
        saveDateBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                var dateInstance = e.target.getAttribute('data-dateinstance');
                var timetable = e.target.getAttribute('data-timetable');
                var dateField = e.target.getAttribute('data-datefield');
                
                if (dateInstance == 'datepickerInitInstance') {
                    var date = new Date(datepickerInitInstance.getDate());
                } else {
                    var date = new Date(datepickerEndInstance.getDate());
                }
                
                var dateString = date.toISOString().split('T')[0];
                var timeSlotItems = document.getElementsByName(timetable);
                var checkedRadio = Array.from(timeSlotItems).find(
                    (radio) => radio.checked
                );
    
                console.log(dateString + ' ' + checkedRadio.value);
                
                document.getElementById(dateField).value = dateString + ' ' + checkedRadio.value;
            });
        });
    }


 

    // Delete image
    const deleteImage = document.querySelectorAll('.delete-image');

    if (deleteImage != undefined) {
        deleteImage.forEach(element => {
            element.addEventListener('click', function(e){
                var nodeElement = e.target.dataset.element;
                
                var image = document.getElementById(nodeElement);
                image.remove();
        
                var hiddenField = document.getElementById(nodeElement + '_hidden');
                hiddenField.value = 1;
            });
        });
        
    }
});

  
