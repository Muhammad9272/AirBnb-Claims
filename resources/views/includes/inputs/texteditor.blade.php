
@pushOnce('partial_css')

@endPushOnce

    
@pushOnce('partial_script')    
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
        var copiedStyle = {}; // Store copied styles
        function getSelectedStyles() {
            // style = getComputedStyle(window.getSelection().anchorNode.parentElement);

            var selection = window.getSelection();
            if (!selection.rangeCount) return null;

            // Get the common ancestor node of the selection
            var parentNode = selection.getRangeAt(0).commonAncestorContainer;

            // If the node is a Text node, get its parent element
            if (parentNode.nodeType === 3) {
                parentNode = parentNode.parentNode;
            }

            var style = getComputedStyle(parentNode);

            var allStyles = {};
            //Loop through all properties and capture their values
            for (let i = 0; i < style.length; i++) {
                let prop = style[i];
                let value = style.getPropertyValue(prop);

                // Filter out default or unwanted styles. This is a basic example and you can add more.
                if (value !== 'initial' && value !== 'auto' && value !== '0px' && !prop.startsWith('-webkit-')) {
                    allStyles[prop] = value;
                }
            }
             return allStyles;
            // return {
            //     fontWeight: style.fontWeight,
            //     fontStyle: style.fontStyle,
            //     textDecoration: style.textDecoration,
            //     textDecorationLine: style.textDecorationLine,  // Additional properties for decoration
            //     textDecorationStyle: style.textDecorationStyle,
            //     textDecorationColor: style.textDecorationColor,
            //     color: style.color,
            //     backgroundColor: style.backgroundColor,
            //     fontSize: style.fontSize,
            //     fontFamily: style.fontFamily,
            //     textTransform: style.textTransform,
            //     letterSpacing: style.letterSpacing,
            //     lineHeight: style.lineHeight,
            //     textAlign: style.textAlign,
            //     textIndent: style.textIndent,
            //     textShadow: style.textShadow,
            //     // ... and more based on your needs
            // };
           
        }
        
        var FormatPainterButton = function (context) {
            var ui = $.summernote.ui;

            var button = ui.button({
                contents: '<i class="fa fa-paint-brush"/> Format Painter',
                tooltip: 'Format Painter',
                click: function () {
                    copiedStyle = getSelectedStyles();
                    console.log(copiedStyle);
                }
            });

            return button.render(); // Return button as a jQuery object
        };
        
        function initTextEditor() {            
            $('.mlk-text-editor').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'formatpainter']],
                    // ['view', ['fullscreen', 'codeview']],
                    // ['help', ['help']]
                ],
                fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36', '48', '64'],
                buttons: {
                    formatpainter: FormatPainterButton
                },
                callbacks: {
                    onInit: function () {
                        console.log('Summernote is launched');
                    }
                }
            });

            $(document).on('mouseup', function () {
                if ($('.mlk-text-editor').length === 0) return;
                var selection = window.getSelection();
                // Check if there's a valid selection range
                if (selection.rangeCount > 0) {
                    var range = selection.getRangeAt(0);
                    if (Object.keys(copiedStyle).length !== 0 && selection.toString().trim() !== "") {
                        var span = document.createElement('span');
                        for (var [key, value] of Object.entries(copiedStyle)) {
                            span.style[key] = value;
                        }
                        span.appendChild(range.extractContents());
                        range.insertNode(span);
                        copiedStyle={};
                        selection=null;
                    }
                }
            });
        }
        initTextEditor();
</script>    
@endPushOnce