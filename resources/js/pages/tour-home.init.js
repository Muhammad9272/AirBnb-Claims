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

tourHome.addStep({
    title: "Welcome Back !",
    text: "Welcome to thte tutorial for the Assessment Pass Education System. <br/> <br/>  This tutorial will show you everything you need to finding your target job.",
    attachTo: {
        element: "#navbar-nav",
        on: "right",
    },
    buttons: [
        {
            text: "Exit Tutorial",
            classes: "btn btn-success",
            action: tourHome.complete,
        },
        {
            text: "Next",
            classes: "btn btn-success",
            // action: tourHome.next,
            // href: "/employ-guide",
            action() {
                console.log("working");
                window.location.href = "/companies-list";
                return this.next();
            },
        },
    ],
});
// end step 1

tourHome.start();
