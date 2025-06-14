
@pushOnce('partial_css')
<style type="text/css">
  canvas{
            display: none;
        }
</style>
@endPushOnce

<div class="col-md-3 control-label pdfContainer">
    <input id='pdf' type='file' name="pdfFile" accept="application/pdf" />
</div>
@if(isset($data->pdf_file))
<a href="{{ Helpers::image($data->pdf_file, 'quiz/pdf_files/') }}" class="mt-2" download="true">
    Click here to Download
</a>
@endif

<div class="col-md-4 control-label" style="text-align:left;">
    <p class="datauploader" style="margin: 0;font-weight: 600;color: #32c5d2;"></p>
</div>

@pushOnce('partial_script')   
<!-- PDF.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script>
    // Set the worker src 
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';
</script>

<!-- Your Custom Script -->
<script type="text/javascript">
    // function attachPDFChangeEvent(){
    //     const pdfInput = document.getElementById('pdf');
    //     const dataUploader = document.querySelector('.datauploader');
    //     const submitBtn = document.querySelector('.submit-btn');
    //     const pdfContainer = document.querySelector('.pdfContainer');

    //     if (!pdfInput) return;

    //     pdfInput.addEventListener('change', handlePDFChange);

    //     function handlePDFChange(event) {
    //         const file = event.target.files[0];

    //         if (!file) return;

    //         dataUploader.textContent = "Processing ...";
    //         submitBtn.disabled = true;

    //         const fileReader = new FileReader();
    //         fileReader.onload = function () {
    //             processPDF(fileReader.result);
    //         };
    //         fileReader.readAsArrayBuffer(file);
    //     }

    //     async function processPDF(data) {
    //         const pdf = await pdfjsLib.getDocument(data).promise;
    //         const canvasData = [];

    //         for (let i = 1; i <= pdf.numPages; i++) {
    //             const page = await pdf.getPage(i);
    //             const canvas = document.createElement("canvas");
    //             const input = document.createElement("input");

    //             canvas.id = 'canvas' + i;
    //             input.id = 'input' + i;
    //             input.name = 'pdfs[]';
    //             input.type = 'hidden';

    //             pdfContainer.appendChild(canvas);
    //             pdfContainer.appendChild(input);

    //             const viewport = page.getViewport({ scale: 1.5 });
    //             canvas.height = viewport.height;
    //             canvas.width = viewport.width;
    //             const context = canvas.getContext('2d');

    //             await page.render({ canvasContext: context, viewport: viewport }).promise;
    //             canvasData.push(canvas.toDataURL('image/jpeg'));
    //             input.value = canvasData[canvasData.length - 1];
    //         }

    //         handleProcessingComplete();
    //     }

    //     function handleProcessingComplete() {
    //         dataUploader.textContent = "Done";
    //         submitBtn.disabled = false;
    //     }
  
    // }
    // if( document.getElementById('pdf')){
    //    attachPDFChangeEvent();
    // }

    function attachPDFChangeEvent() {
        const pdfInput = document.getElementById('pdf');
        const dataUploader = document.querySelector('.datauploader');
        const submitBtn = document.querySelector('.submit-btn');
        const pdfContainer = document.querySelector('.pdfContainer');
        const batchSize = 5;  // Number of pages processed per batch

        if (!pdfInput) return;

        pdfInput.addEventListener('change', handlePDFChange);

        function handlePDFChange(event) {
            //clear old inputss
            clearPreviousCanvasesAndInputs();

            const file = event.target.files[0];

            if (!file) return;
            if (file.size > 20971520) {
                alert('File size exceeds the allowed limit of 15MB.');
                pdfInput.value = '';  // Clear the input
                return;
            }

            dataUploader.textContent = "Processing ...";
            submitBtn.disabled = true;

            const fileReader = new FileReader();
            fileReader.onload = function () {
                processPDF(fileReader.result);
            };
            fileReader.readAsArrayBuffer(file);
        }

        async function processPDF(data) {
            const pdf = await pdfjsLib.getDocument(data).promise;

            let promises = [];
            for (let i = 1; i <= pdf.numPages; i++) {
                promises.push(processPage(pdf, i));

                if (promises.length === batchSize || i === pdf.numPages) {
                    await Promise.all(promises);  // Wait for the batch to finish processing
                    promises = [];  // Reset the batch
                }
            }

            handleProcessingComplete();
        }

        async function processPage(pdf, pageNum) {
            const page = await pdf.getPage(pageNum);
            const canvas = document.createElement("canvas");
            const input = document.createElement("input");

            canvas.id = 'canvas' + pageNum;
            input.id = 'input' + pageNum;
            input.name = 'pdfs[]';
            input.type = 'hidden';

            pdfContainer.appendChild(canvas);
            pdfContainer.appendChild(input);

            const viewport = page.getViewport({ scale: 2.0 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            const context = canvas.getContext('2d');

            await page.render({ canvasContext: context, viewport: viewport }).promise;
            const canvasData = canvas.toDataURL('image/jpeg');
            input.value = canvasData;

            return canvasData;
        }

        function handleProcessingComplete() {
            dataUploader.textContent = "Done";
            submitBtn.disabled = false;
        }

        // This function removes all previous hidden inputs named 'pdfs[]'
        // function clearPreviousPDFInputs() {
        //     const oldInputs = document.querySelectorAll('input[name="pdfs[]"]');
        //     oldInputs.forEach(input => {
        //         input.remove();
        //     });
        // }


        // New function to clear the previous canvases and inputs
        function clearPreviousCanvasesAndInputs() {
            const oldCanvases = pdfContainer.querySelectorAll('canvas');
            oldCanvases.forEach(canvas => {
                canvas.remove();
            });
            
            const oldInputs = pdfContainer.querySelectorAll('input[name="pdfs[]"]');
            oldInputs.forEach(input => {
                input.remove();
            });
        }
    }

    if (document.getElementById('pdf')) {
        attachPDFChangeEvent();
    }


// function attachPDFChangeEvent() {
//     const pdfInput = document.getElementById('pdf');
//     const dataUploader = document.querySelector('.datauploader');
//     const submitBtn = document.querySelector('.submit-btn');
//     const pdfContainer = document.querySelector('.pdfContainer');
//     const batchSize = 10;  // Number of images per batch

//     if (!pdfInput) return;

//     pdfInput.addEventListener('change', handlePDFChange);

//     async function handlePDFChange(event) {
//         clearPreviousPDFInputs();  // Clear old hidden inputs

//         const file = event.target.files[0];
//         if (!file) return;

//         dataUploader.textContent = "Processing ...";
//         submitBtn.disabled = true;

//         const pdf = await pdfjsLib.getDocument({ url: URL.createObjectURL(file) }).promise;
//         const promises = [];
//         for (let i = 1; i <= pdf.numPages; i++) {
//             promises.push(processPage(pdf, i));
//         }

//         const images = await Promise.all(promises);
        
//         // Batch Upload
//         for (let i = 0; i < images.length; i += batchSize) {
//             const batch = images.slice(i, i + batchSize);
//             handleProcessingComplete(batch);
//         }
//     }

//     async function processPage(pdf, pageNum) {
//         const page = await pdf.getPage(pageNum);
//         const canvas = document.createElement("canvas");
//         const context = canvas.getContext('2d');
//         const viewport = page.getViewport({ scale: 1.5 });

//         canvas.height = viewport.height;
//         canvas.width = viewport.width;

//         await page.render({ canvasContext: context, viewport: viewport }).promise;

//         return new Promise(resolve => {
//             canvas.toBlob(blob => {
//                 pdfContainer.appendChild(createHiddenInput(blob));
//                 resolve(blob);
//             }, 'image/png');
//         });
//     }

//     function createHiddenInput(blob) {
//         const input = document.createElement("input");
//         input.name = 'pdfs[]';
//         input.type = 'hidden';
//         input.value = URL.createObjectURL(blob);
//         return input;
//     }

//     function handleProcessingComplete(batch) {
//         dataUploader.textContent = "Done";
//          submitBtn.disabled = false;
//         const formData = new FormData();
//         batch.forEach((blob, index) => {
//             formData.append('pdfs[]', blob, 'pdf_image_' + (index + 1) + '.png');
//         });
//         // Make the AJAX request with formData to your backend for each batch
//         // After the upload is complete:
//         // You might want to add some indication that all batches are uploaded.
//         // Consider handling errors here to inform the user if something goes wrong.
//         // After upload, you can revoke the URLs:
//         batch.forEach(blob => URL.revokeObjectURL(blob));
//     }
//      // This function removes all previous hidden inputs named 'pdfs[]'
//     function clearPreviousPDFInputs() {
//         const oldInputs = document.querySelectorAll('input[name="pdfs[]"]');
//         oldInputs.forEach(input => {
//             input.remove();
//         });
//     }
// }

// if (document.getElementById('pdf')) {
//     attachPDFChangeEvent();
// }

// function attachPDFChangeEvent11() {
//     const pdfInput = document.getElementById('pdf');
//     const dataUploader = document.querySelector('.datauploader');
//     const submitBtn = document.querySelector('.submit-btn');
//     const pdfContainer = document.querySelector('.pdfContainer');
//     const batchSize = 10;  // Number of images per batch

//     if (!pdfInput) return;

//     pdfInput.addEventListener('change', handlePDFChange);

//     function blobToFile(blob, fileName) {
//         return new File([blob], fileName, {type: blob.type, lastModified: Date.now()});
//     }


//     async function handlePDFChange(event) {
//         clearPreviousPDFInputs();  // Clear old hidden inputs

//         const file = event.target.files[0];
//         if (!file) return;

//         dataUploader.textContent = "Processing ...";
//         submitBtn.disabled = true;

//         const pdf = await pdfjsLib.getDocument({ url: URL.createObjectURL(file) }).promise;
//         const promises = [];
//         for (let i = 1; i <= pdf.numPages; i++) {
//             promises.push(processPage(pdf, i));
//         }

//         const images = await Promise.all(promises);
        
//         // Batch Upload (though, with your new approach, this might not be necessary)
//         for (let i = 0; i < images.length; i += batchSize) {
//             const batch = images.slice(i, i + batchSize);
//             handleProcessingComplete(batch);
//         }
//     }

//     async function processPage(pdf, pageNum) {
//         const page = await pdf.getPage(pageNum);
//         const canvas = document.createElement("canvas");
//         const context = canvas.getContext('2d');
//         const viewport = page.getViewport({ scale: 1.5 });

//         canvas.height = viewport.height;
//         canvas.width = viewport.width;

//         await page.render({ canvasContext: context, viewport: viewport }).promise;

//         return new Promise(resolve => {
//             canvas.toBlob(blob => {
//                 pdfContainer.appendChild(createFileInput(blob, 'pdf_image_' + pageNum + '.png'));
//                 resolve(blob);
//             }, 'image/png');
//         });
//     }

//     function createFileInput(blob, fileName) {
//         const input = document.createElement("input");
//         input.name = 'pdfs[]';
//         input.type = 'file';
//         input.style.display = 'none'; // Hide it

//         // Convert blob to file
//         const file = blobToFile(blob, fileName);

//         // Attach the file to the input
//         const dataTransfer = new DataTransfer();
//         dataTransfer.items.add(file);
//         input.files = dataTransfer.files;

//         return input;
//     }

//     function handleProcessingComplete(batch) {
//         dataUploader.textContent = "Done";
//         submitBtn.disabled = false;

//         batch.forEach(blob => URL.revokeObjectURL(blob));  // No need to use formData since you're not making AJAX calls
//     }

//     // This function removes all previous hidden inputs named 'pdfs[]'
//     function clearPreviousPDFInputs() {
//         const oldInputs = document.querySelectorAll('input[name="pdfs[]"]');
//         oldInputs.forEach(input => {
//             input.remove();
//         });
//     }
// }

// if (document.getElementById('pdf')) {
//     attachPDFChangeEvent11();
// }

</script>

@endPushOnce