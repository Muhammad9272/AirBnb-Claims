/*

Author: Taimoor Salyhal


File: tour init js
*/

var tour = new Shepherd.Tour({
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

if (document.querySelector("#navbar-nav"))
    tour.addStep({
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
                action: tour.complete,
            },
            {
                text: "Next",
                classes: "btn btn-success",
                // action: tour.next,
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

if (document.querySelector("#companiesList2"))
    tour.addStep({
        title: "Register your account",
        text: "Get your Free Velzon account now.",
        attachTo: {
            element: "#companiesList2",
            on: "bottom",
        },
        buttons: [
            {
                text: "Back",
                classes: "btn btn-light",
                action: tour.back,
            },
            {
                text: "Next",
                classes: "btn btn-success",
                // href: "/employ-guide",
                action: tour.next,
            },
        ],
    });

// end step 2

if (document.querySelector("#login-tour"))
    tour.addStep({
        title: "Login your account",
        text: "Sign in to continue to Velzon.",
        attachTo: {
            element: "#login-tour",
            on: "bottom",
        },
        buttons: [
            {
                text: "Back",
                classes: "btn btn-light",
                action: tour.back,
            },
            {
                text: "Next",
                classes: "btn btn-success",
                action: tour.next,
            },
        ],
    });

// end step 3
if (document.querySelector("#getproduct-tour"))
    tour.addStep({
        title: "Get yout Product",
        text: "Sign in to continue to Velzon.",
        attachTo: {
            element: "#getproduct-tour",
            on: "bottom",
        },
        buttons: [
            {
                text: "Back",
                classes: "btn btn-light",
                action: tour.back,
            },
            {
                text: "Next",
                classes: "btn btn-success",
                action: tour.next,
            },
        ],
    });
// end step 4

if (document.querySelector("#thankyou-tour"))
    tour.addStep({
        title: "Thank you !",
        text: "Sign in to continue to Velzon.",
        attachTo: {
            element: "#thankyou-tour",
            on: "bottom",
        },
        buttons: [
            {
                text: "Back",
                classes: "btn btn-light",
                action: tour.back,
            },
            {
                text: "Thank you !",
                classes: "btn btn-primary",
                action: tour.complete,
            },
        ],
    });
// end step 5

tour.start();
