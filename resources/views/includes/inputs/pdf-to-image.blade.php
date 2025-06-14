
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
   
    function attachPDFChangeEvent() {
    
      var convasdata=[];
      var pdf = document.getElementById('pdf');
      pdf.onchange = function(ev) {
          $('.datauploader').text("Processing ...");
          $('.submit-btn').attr('disabled', true);

          if (file = document.getElementById('pdf').files[0]) {
              fileReader = new FileReader();
              fileReader.onload = function(ev) {
                  pdfjsLib.getDocument(fileReader.result).promise.then(function getPdfHelloWorld(pdf) {

                      for (var vrt = 1; vrt <= pdf.numPages; vrt++){
                          var canvas=document.createElement("canvas");
                          canvas.setAttribute('id','canvas'+vrt);
                          document.body.appendChild(canvas);

                          var input=document.createElement("input");
                          input.setAttribute('id','input'+vrt);
                          input.setAttribute('name','pdfs[]');
                          input.setAttribute('type','hidden');
                          document.body.appendChild(input);
                          const menu = document.querySelector('.pdfContainer');
                          menu.appendChild(input);

                          geturl(pdf,vrt);                
                      }

                      console.log(convasdata);

                      var refreshIntervalId=setInterval(runFunction,1000);
                      function runFunction() {      
                          if( $("#input1").val()) {
                              $('.submit-btn').attr('disabled',false); 
                              $('.datauploader').text("Done");
                              clearInterval(refreshIntervalId);
                          }                  
                      }
                  }, function(error){
                      console.log(error);
                  });
              };
              fileReader.readAsArrayBuffer(file);
          }
      }
      function geturl(pdf, i) {
          pdf.getPage(i).then(function getPageHelloWorld(page) {
              var scale = 1.5;
              var viewport = page.getViewport({ scale: scale });

              var canvas = document.getElementById('canvas' + i);
              var context = canvas.getContext('2d');
              canvas.height = viewport.height;
              canvas.width = viewport.width;                  

              // Render PDF page into canvas context                 
              var task = page.render({canvasContext: context, viewport: viewport});
              task.promise.then(function() {
                  convasdata.push(canvas.toDataURL('image/jpeg'));
                  document.getElementById('input' + i).value = canvas.toDataURL('image/jpeg');
              });
          });
      }
    };
    if( document.getElementById('pdf')){
       attachPDFChangeEvent();
    }
   
</script>
@endPushOnce