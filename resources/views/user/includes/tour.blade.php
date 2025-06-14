@pushOnce('partial_script') 
    <script src="{{ URL::asset('/assets/libs/shepherd.js/shepherd.js.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/tour/tour.init.js') }}"></script> --}}
    <script>

        // Check if the tour has already been completed
        var tourCompleted = "{{ auth()->user()->tour_completed }}";

        if (tourCompleted !== "1") {
            var tourHome = new Shepherd.Tour({
                defaultStepOptions: {
                    cancelIcon: {
                        enabled: true,
                    },

                    classes: "shadow-md bg-purple-dark",
                    scrollTo: {
                        behavior: "smooth",
                        block: "center",
                    },
                },
                useModalOverlay: {
                    enabled: true,
                },
            });

            if (document.querySelector("{{ $step['element'] }}"))
                tourHome.addStep({
                    title: "{{ $step['title'] }}",
                    text: "{!! $step['text'] !!}",
                    attachTo: {
                        element: "{{ $step['element'] }}",
                        on: "{{ $step['position'] }}",
                        // tetherOptions: {
                        //     attachment: "top right",
                        //     targetAttachment: "bottom right",
                        //     offset: "10px 10px", // Adjust the margin as needed
                        // },
                    },
                    popperOptions: {
                        modifiers: [
                            {
                                name: 'offset',
                                options: {
                                    offset: [10, 10], // Adjust the margin as needed
                                },
                            },
                        ],
                    },

                    buttons: [{
                            text: "Exit Tutorial",
                            classes: "btn btn-danger",
                            action: completeTour,
                        },
                        @if(isset($step['prev_button']))
                        {
                            text: "Prev",
                            classes: "btn btn-success ms-auto",
                            action() {
                                window.location.href = "{{$step['prev_button']}}";
                                return this.next();
                            },
                        },
                        @endif
                        @if(isset($step['next_button']))
                        {
                            text: "Next",
                            classes: "btn btn-warning text-white ms-auto",
                            action() {
                                window.location.href = "{{ $step['next_button'] }}";
                                return this.next();
                            },
                        },
                        @endif
                        @if(isset($step['finish_button']))
                        {
                            text: "Finish",
                            classes: "btn btn-success text-white ms-auto",
                            action() {
                                completeTour(() => {
                                    window.location.href = "{{ $step['finish_button'] }}";
                                });
                            },
                        },
                        @endif
                    ],
                    highlight: true, // Highlight the sidebar menu item
                    // beforeShowPromise: function () {
                    //     return new Promise(function (resolve) {
                    //         @if(isset($step['sidebarElement']))
                    //         $("{{$step['sidebarElement']}}").addClass("tour-highlight-active");
                    //         @endif
                    //         resolve();
                    //     });
                    // },
                    // onHide: function () {
                    //      @if(isset($step['sidebarElement']))
                    //     $("{{$step['sidebarElement']}}").removeClass("tour-highlight-active");
                    //     @endif
                    // },
                });
            // Add event listener for close button click
            tourHome.on('cancel', completeTour);
            tourHome.start();
        }
        // function completeTour() {
        //     // Save tour completion status in the database
        //     fetch("{{ route('user.saveTourCompletion') }}", {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //         },
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             tourHome.complete();
        //         }
        //     })
        //     .catch(error => {
        //         console.log(error);
        //     });
        // }
        function completeTour(callback) {
            // Save tour completion status in the database
            fetch("{{ route('user.saveTourCompletion') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tourHome.complete();
                    if (callback) callback();
                }
            })
            .catch(error => {
                console.log(error);
            });
        }

    </script> 
    
@endPushOnce    