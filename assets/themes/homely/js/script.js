(function ($) {
    "use strict";

    $(function () {
        const $inputs = $('input[name="datefilter"]');

        if (!$inputs.length) return;

        // Initialize all pickers
        $inputs.each(function () {
            const $input = $(this);
            const $dateBox = $input
                .closest(".destination-search")
                .find(".date");

            // Initialize daterangepicker
            $input.daterangepicker({
                autoUpdateInput: false,
                minDate: moment(),
                locale: {
                    cancelLabel: "Clear",
                    format: "DD/MM/YYYY",
                },
            });

            // When date is applied
            $input.on("apply.daterangepicker", function (ev, picker) {
                $(this).val(
                    picker.startDate.format("DD/MM/YYYY") +
                        " - " +
                        picker.endDate.format("DD/MM/YYYY")
                );

                if ($dateBox.length) $dateBox.removeClass("active");
                $(".daterangepicker").removeClass("show");
            });

            // When canceled
            $input.on("cancel.daterangepicker", function () {
                $(this).val("");
                if ($dateBox.length) $dateBox.removeClass("active");
                $(".daterangepicker").removeClass("show");
            });

            // Handle click if inside .destination-search
            if ($dateBox.length) {
                $dateBox.on("click", function (e) {
                    e.stopPropagation();
                    $dateBox.addClass("active");
                    $input.focus();
                    $input.data("daterangepicker").show();
                    $(".daterangepicker").addClass("show");

                    const countBox = $(".destination-search .count");
                    countBox.removeClass("active");
                });
            }
        });

        // 🔥 Prevent closing when navigating months (mousedown fires before document click)
        $(document).on(
            "mousedown",
            ".daterangepicker .prev, .daterangepicker .next",
            function (e) {
                e.stopPropagation();
            }
        );

        // Prevent closing when clicking anywhere inside picker
        $(document).on("mousedown", ".daterangepicker", function (e) {
            e.stopPropagation();
        });

        // Hide picker when clicking outside both picker & .destination-search
        $(document).on("mousedown", function (e) {
            if (
                !$(e.target).closest(".daterangepicker").length &&
                !$(e.target).closest(".destination-search .date").length
            ) {
                $('input[name="datefilter"]').each(function () {
                    const drp = $(this).data("daterangepicker");
                    if (drp) drp.hide();
                });
                $(".daterangepicker").removeClass("show");
                $(".destination-search .date").removeClass("active");
            }
        });
    });

    // daterangepicker 2

    $(document).ready(function () {
        $(".imageInput").on("change", function (event) {
            const files = event.target.files;
            const previewContainer = $(".previewContainer");

            previewContainer.empty(); // Clear previous images

            if (files.length > 0) {
                $.each(files, function (index, file) {
                    if (file.type.startsWith("image/")) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = $("<img>")
                                .attr("src", e.target.result)
                                .css({
                                    width: "100px",
                                    height: "100px",
                                    objectFit: "cover",
                                    borderRadius: "8px",
                                });
                            previewContainer.append(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    });

    // page fold
    $(".hostButton").hover(
        function () {
            TweenLite.to($(this).find(".pageFoldRight"), 0.3, {
                width: "35px",
                height: "35px",
                backgroundImage:
                    "linear-gradient(45deg, #fefefe 0%,#f2f2f2 49%,#ffffff 50%,#ffffff 100%)",
            });
        },
        function () {
            TweenLite.to($(this).find(".pageFoldRight"), 0.3, {
                width: "0px",
                height: "0px",
            });
        }
    );
    // page fold

    // .listing-house-list
    $(document).ready(function () {
        $(".listing-house-list li a").click(function (e) {
            e.preventDefault();
            $(".listing-house-list li a").removeClass("active");
            $(this).addClass("active");
        });
    });
    // .listing-house-list

    // // chart

    $(function () {
        // Equivalent to $(document).ready()
        var canvas = $("#earning"); // jQuery object
        if (canvas.length) {
            // Check if the element exists
            var ctx = canvas[0].getContext("2d"); // Get the raw DOM element before using getContext

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Jan", "Feb", "March", "April", "May", "June"],
                    datasets: [
                        {
                            label: "Earn per month",
                            data: [1200, 1900, 3000, 5000, 6000, 8000],
                            borderWidth: 1,
                            backgroundColor: [
                                "rgba(255,99,132, 1)",
                                "rgba(54,162,235, 1)",
                                "rgba(255,206,86, 1)",
                                "rgba(75,192,192, 1)",
                                "rgba(253,102,64, 1)",
                                "rgba(255,159,132, 1)",
                            ],
                        },
                    ],
                },
                options: {
                    responsive: true,
                },
            });
        }
    });

    // chart

    // modal inner animation
    $(document).ready(function () {
        $("#hostModal").on("shown.bs.modal", function () {
            setTimeout(function () {
                $(".host-modal-info").addClass("animate");
            });
        });

        $("#hostModal").on("hidden.bs.modal", function () {
            $(".host-modal-info").removeClass("animate");
        });
    });
    // modal inner animation

    // progres circle
    if ($(".banner-progress").length) {
        $(function () {
            $(".circlechart").circlechart();
        });
    }
    // progres circle

    //Search Popup
    if ($("#search-popup").length) {
        //Show Popup
        $(".search-btn").on("click", function () {
            $("#search-popup").addClass("popup-visible");
        });
        $(document).keydown(function (e) {
            if (e.keyCode === 27) {
                $("#search-popup").removeClass("popup-visible");
            }
        });
        //Hide Popup
        $(".close-search,.search-popup .overlay-layer").on(
            "click",
            function () {
                $("#search-popup").removeClass("popup-visible");
            }
        );
    }
    //Search Popup

    // Search Box
    if ($(".location-search-box").length) {
        const countBox = $(".destination-search .count");
        console.log(countBox)
        document.querySelectorAll(".location-search-box")
            .forEach((searchBox) => {
                const selectOption = searchBox.querySelector(".select-option");
                const soValue = searchBox.querySelector(".soValue");
                const optionSearch = searchBox.querySelector(".optionSearch");
                const options = searchBox.querySelector(".search-options");

                const optionsList =
                    searchBox.querySelectorAll(".search-options li");

                selectOption.addEventListener("click", function (event) {
                    searchBox.classList.add("active");
                    soValue.select();
                    event.stopPropagation();
                });

                window.addEventListener("click", function () {
                    searchBox.classList.remove("active");
                });

                optionsList.forEach(function (optionsListSingle) {
                    optionsListSingle.addEventListener("click", function () {
                        const text =
                            optionsListSingle.querySelector(".country");
                        const textContent = text.textContent;
                        soValue.value = textContent;

                        searchBox.classList.remove("active");
                    });
                });

                optionSearch.addEventListener("keyup", function () {
                    var filter, li, i, textValue;
                    filter = optionSearch.value.toUpperCase();
                    li = options.getElementsByTagName("li");
                    for (i = 0; i < li.length; i++) {
                        const liCount = li[i];
                        textValue = liCount.textContent || liCount.innerText;
                        if (textValue.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                        } else {
                            li[i].style.display = "none";
                        }
                    }
                });
            });
    }
    // Search Box

    // nice select
    $(document).ready(function () {
        $(".nice-select").niceSelect();
    });
    // nice select

    // select2 start
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var flagUrl = $(state.element).data("flag") || "";

        var $state = $(
            "<span>" +
                (flagUrl
                    ? '<img src="' + flagUrl + '" class="img-flag" /> '
                    : "") +
                state.text +
                "</span>"
        );

        var subtitle = $(state.element).data("subtitle");
        if (subtitle) {
            $state.append(
                '<br /><span class="subtitle">' + subtitle + "</span>"
            );
        }

        return $state;
    }

    $(".cmn-select2-image2, .cmn-select2-image3, .cmn-select2-image4").select2({
        templateResult: formatState,
        templateSelection: formatState,
        minimumResultsForSearch: -1,
    });

    $("#saveLangCurrency").on("click", function () {
        const route = $(".lang-currency-btn").data("route");
        const language = $("#languageSelect").val();
        const currency = $("#currencySelect").val();
        console.log(route);
        $.ajax({
            url: route,
            method: "POST",
            data: {
                language: language,
                currency: currency,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    Notiflix.Notify.success("Language & currency updated!");
                    location.reload();
                } else {
                    Notiflix.Notify.failure("Something went wrong!");
                }
            },
            error: function () {
                Notiflix.Notify.failure("Server error, please try again!");
            },
        });
    });
    // select2 end

    // INCREMENT DECREMENT COUNT START
    document.addEventListener("DOMContentLoaded", function () {
        // Function to handle count functionality
        function setupCountSection(
            selector,
            incrementSelector,
            decrementSelector
        ) {
            const countElements = document.querySelectorAll(selector);
            const incrementButtons =
                document.querySelectorAll(incrementSelector);
            const decrementButtons =
                document.querySelectorAll(decrementSelector);

            let count = 0;

            function updateCount() {
                countElements.forEach((element) => {
                    element.textContent = count;
                });
            }

            incrementButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    count++;
                    updateCount();
                });
            });

            decrementButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    if (count > 0) {
                        count--;
                        updateCount();
                    }
                });
            });
        }

        // Setup count functionality for adults
        setupCountSection(".adult", ".increment", ".decrement", 0);

        // Setup count functionality for children
        setupCountSection(".childeren", ".incrementTwo", ".decrementTwo", 0);

        // Setup count functionality for room
        setupCountSection(".room", ".incrementThree", ".decrementThree", 0);
    });
    // INCREMENT DECREMENT COUNT END

    // count-container toggle
    $(document).ready(function () {
        const $countBox = $(".destination-search .count"); // all count wrappers

        // Toggle the clicked .count only (delegated)
        $(document).on("click", ".destination-search .count", function (e) {
            e.stopPropagation();
            $(this).toggleClass("active");
        });

        // Prevent clicks inside .count-container from bubbling to document
        // (delegated — works for dynamically added nodes too)
        $(document).on("click", ".count-container", function (e) {
            e.stopPropagation();
        });

        // Click outside: close all count boxes
        $(document).on("click", function (e) {
            if (
                !$(e.target).closest(".destination-search .count").length &&
                !$(e.target).closest(".count-container").length
            ) {
                $countBox.removeClass("active");
            }
        });
    });

    // count-container toggle

    //CART OFFCANVAS
    if ($("#cart-offcanvas").length) {
        //Show Popup
        $(".cart-toggler").on("click", function () {
            $("#cart-offcanvas").addClass("popup-visible");
        });
        $(document).keydown(function (e) {
            if (e.keyCode === 27) {
                $("#cart-offcanvas").removeClass("popup-visible");
            }
        });
        //Hide Popup
        $(".close-search,.cart-offcanvas .overlay-layer").on(
            "click",
            function () {
                $("#cart-offcanvas").removeClass("popup-visible");
            }
        );
    }
    // CART OFFCANVAS

    // ISOTOP STARTS
    $(document).ready(function () {
        var $grid = $(".listing-row").isotope({
            itemSelector: ".grid-item",
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: 1,
            },
        });

        // Filter items on button click
        $(".filter-button-group").on("click", "button", function () {
            var filterValue = $(this).attr("data-filter");
            $grid.isotope({ filter: filterValue });
        });

        // Set default filter to ".cleaning" on page load
        var defaultFilter = ".cleaning";
        $grid.isotope({ filter: defaultFilter });

        $(".filter-button-group button").removeClass("active");
        $('.filter-button-group button[data-filter=".cleaning"]').addClass(
            "active"
        );

        // Active class toggle on button click
        $(".filter-button-group button").on("click", function (event) {
            $(this).siblings(".active").removeClass("active");
            $(this).addClass("active");
            event.preventDefault();
        });
    });
    // ISOTOP ENDS

    // evo calendar
    if ($(".evo-calender").length) {
        $("#evoCalendar").evoCalendar({
            theme: "Midnight Blue",
            calendarEvents: [
                {
                    id: "event1",
                    name: "Independence Day",
                    date: "December/16/2022",
                    description:
                        "lwjd wqkjdwui xiwugdwq iwqudghwqui xiuwqghdiwuqo",
                    type: "holiday",
                    everyYear: true,
                },
                {
                    id: "event2",
                    name: "Independence Day",
                    date: "December/16/2022",
                    description:
                        "lwjd wqkjdwui xiwugdwq iwqudghwqui xiuwqghdiwuqo",
                    type: "holiday",
                    everyYear: true,
                },
                {
                    name: "Surice Offer",
                    badge: "Take your offer",
                    date: ["December/16/2022"],
                    description: "Vacation leave for 3 days.",
                    type: "event",
                    color: "#63d867",
                },
                {
                    name: "Surice Offer",
                    badge: "Take your offer",
                    date: ["December/13/2025", "December/15/2025"],
                    description: "Vacation leave for 3 days.",
                    type: "event",
                    color: "#63d867",
                },
            ],
        });
    }
    // evo calendar

    //--- LOAD MORE STARTS ---//
    $(".item-list").slice(0, 3).show();

    $(".load-more").click(function () {
        $(".item-list:hidden").slice(0, 1).slideDown(300);

        // hide btn after fully loaded
        if ($(".item-list:hidden").length == 0) {
            $(this).fadeOut(300);
        }
    });
    //--- LOAD MORE ENDS ---//

    // BAR FILLAR
    if ($(".progress-bar").length) {
        document.addEventListener("DOMContentLoaded", function () {
            const progressBars = document.querySelectorAll(".progress-bar");

            function showProgress() {
                progressBars.forEach((progressBar) => {
                    const value = progressBar.dataset.progress;
                    progressBar.style.opacity = 1;
                    progressBar.style.width = `${value}%`;
                });
            }

            showProgress();
        });
    }
    // BAR FILLAR

    // Social share start
    $("#shareBlock").socialSharingPlugin({
        urlShare: window.location.href,
        description: $("meta[name=description]").attr("content"),
        title: $("title").text(),
    });
    // Social share end

    // swiper thumb
    var swiper = new Swiper(".projectSwiper", {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".projectSwiper2", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
    // swiper thumb

    // input check value change
    $(document).ready(function () {
        $(".checkmark").on("click", function () {
            var color = $(this).siblings("input").data("color");
            $(".change-value").text(color);
            $(".checkbox").prop("checked", false);
            $(this).siblings("input").prop("checked", true);
        });
    });
    // input check value change

    // odommeter
    if ($(".odometer").length) {
        var odo = $(".odometer");
        odo.each(function () {
            $(this).appear(function () {
                var countNumber = $(this).attr("data-count");
                $(this).html(countNumber);
            });
        });
    }
    // odommeter

    // magnifipopup video
    $(document).ready(function () {
        $(".hv-popup-link").magnificPopup({
            disableOn: 700,
            type: "iframe",
            mainClass: "mfp-fade",
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false,
        });
    });
    // magnifipopup video

    // input field show hide password start
    if ($(".password-box").length) {
        const passwordBoxes = document.querySelectorAll(".password-box");

        passwordBoxes.forEach((box) => {
            const password = box.querySelector(".password");
            const passwordIcon = box.querySelector(".password-icon");

            // Skip if either element is missing to prevent null errors
            if (!password || !passwordIcon) return;

            passwordIcon.addEventListener("click", function () {
                if (password.type === "password") {
                    password.type = "text";
                    passwordIcon.classList.add("fa-eye-slash");
                } else {
                    password.type = "password";
                    passwordIcon.classList.remove("fa-eye-slash");
                }
            });
        });
    }
    // input field show hide password end

    //Hide Loading Box (Preloader)
    function handlePreloader() {
        if ($(".loader-wrap").length) {
            $(".loader-wrap").delay(2000).fadeOut(500);
        }
    }

    $(document).ready(function () {
        handlePreloader();

        if ($(".preloader-close").length) {
            $(".preloader-close").on("click", function () {
                $(".loader-wrap").stop(true).fadeOut(500);
            });
        }
    });
    //Hide Loading Box (Preloader)

    // Menu Style Start
    function dynamicCurrentMenuClass(selector) {
        let FileName = window.location.href.split("/").reverse()[0];

        selector.find("li").each(function () {
            let anchor = $(this).find("a");
            if ($(anchor).attr("href") == FileName) {
                $(this).addClass("current");
            }
        });
        // if any li has .current elmnt add class
        selector.children("li").each(function () {
            if ($(this).find(".current").length) {
                $(this).addClass("current");
            }
        });
        // if no file name return
        if ("" == FileName) {
            selector.find("li").eq(0).addClass("current");
        }
    }
    // Menu Style End

    // dynamic current class
    let mainNavUL = $(".main-menu").find(".navigation");
    dynamicCurrentMenuClass(mainNavUL);

    //Sticky Header Style and Scroll to Top
    function headerStyle() {
        if ($(".main-header").length) {
            var windowpos = $(window).scrollTop();
            var siteHeader = $(".main-header");
            var sticky_header = $(".main-header .sticky-header");
            if (windowpos > 100) {
                siteHeader.addClass("fixed-header");
                sticky_header.addClass("animated slideInDown");
            } else {
                siteHeader.removeClass("fixed-header");
                sticky_header.removeClass("animated slideInDown");
            }
        }
    }
    headerStyle();

    // When sticky header is Scrollig
    $(window).on("scroll", function () {
        headerStyle();
    });
    //Sticky Header Style and Scroll to Top

    // Sticky header button area
    function moveHeaderRightButtons() {
        var headerBtn = $(".header-right-btn-area");
        var placeholder = $(".sticky-header-btn-placeholder");

        if ($(window).scrollTop() > 100) {
            if (!placeholder.find(".header-right-btn-area").length) {
                headerBtn.appendTo(placeholder);
            }
        } else {
            if (
                !$(".header-right-btn-placeholder").find(
                    ".header-right-btn-area"
                ).length
            ) {
                headerBtn.appendTo(".header-right-btn-placeholder");
            }
        }
    }

    if (!window.location.pathname.endsWith("/stays")) {
        moveHeaderRightButtons();

        $(window).on("scroll", function () {
            moveHeaderRightButtons();
        });
    }
    // Sticky header button area

    //Submenu Dropdown Toggle
    if ($(".main-header li.dropdown ul").length) {
        $(".main-header .navigation li.dropdown").append(
            '<div class="dropdown-btn"><span class="fa fa-angle-right"></span></div>'
        );
    }

    //Mobile Nav Hide Show
    if ($(".mobile-menu").length) {
        var mobileMenuContent = $(".main-header .nav-outer .main-menu").html();
        $(".mobile-menu .menu-box .menu-outer").append(mobileMenuContent);
        $(".sticky-header .main-menu").append(mobileMenuContent);
        //Dropdown Button
        $(".mobile-menu li.dropdown .dropdown-btn").on("click", function () {
            $(this).toggleClass("open");
            $(this).prev("ul").slideToggle(500);
            $(this).prev(".megamenu").slideToggle(500);
        });
        //Menu Toggle Btn
        $(".mobile-nav-toggler").on("click", function () {
            $("body").addClass("mobile-menu-visible");
        });
        //Menu Toggle Btn
        $(
            ".mobile-menu .menu-backdrop,.mobile-menu .close-btn,.scroll-nav li a"
        ).on("click", function () {
            $("body").removeClass("mobile-menu-visible");
        });
    }

    // banner slide
    function bannerSlider() {
        // banner slide 01
        if ($(".banner-slider-1").length > 0) {
            // Banner Slider
            var bannerSlider1 = new Swiper(".banner-slider-1", {
                preloadImages: false,
                loop: true,
                centeredSlides: false,
                resistance: true,
                resistanceRatio: 0.6,
                speed: 2400,
                spaceBetween: 0,
                parallax: false,
                effect: "fade",
                autoplay: {
                    delay: 8000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".slider__pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".banner-slider-button-next",
                    prevEl: ".banner-slider-button-prev",
                },
            });
        }
    }
    bannerSlider();

    if ($(".banner-three-swiper").length) {
        var swiper = new Swiper(".banner-three-swiper", {
            grabCursor: true,
            slidesPerView: 2,
            spaceBetween: 30,
            mousewheel: {
                thresholdDelta: 70,
            },
            loop: true,
            autoplay: {
                delay: 3000,
            },
            breakpoints: {
                1199: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                991: {
                    slidesPerView: 1,
                },
            },
        });
    }
    // banner slide

    // Single item Carousel
    if ($(".single-item-carousel").length) {
        var singleItemCarousel = new Swiper(".single-item-carousel", {
            preloadImages: false,
            loop: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 1,
            speed: 1400,
            spaceBetween: 10,
            parallax: false,
            effect: "slide",
            active: "active",
            pagination: {
                el: ".slider__pagination",
                clickable: true,
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    }
    // Single item Carousel

    // two item carousel
    if ($(".two-item-carousel").length) {
        var twoItemCarousel = new Swiper(".two-item-carousel", {
            preloadImages: false,
            loop: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 2,
            speed: 1400,
            spaceBetween: 30,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: false,
            navigation: {
                nextEl: ".swiper-button-next4",
                prevEl: ".swiper-button-prev4",
            },
            pagination: {
                el: ".slider__pagination",
                clickable: true,
            },
            breakpoints: {
                1400: { slidesPerView: 2 },
                991: { slidesPerView: 2 },
                640: { slidesPerView: 1 },
            },
        });
    }

    if ($(".service-rating-carousel").length) {
        var twoItemCarousel = new Swiper(".service-rating-carousel", {
            preloadImages: false,
            loop: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 2,
            speed: 1400,
            spaceBetween: 30,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".slider__pagination",
                clickable: true,
            },
            breakpoints: {
                1400: { slidesPerView: 2 },
                991: { slidesPerView: 2 },
                640: { slidesPerView: 1 },
            },
        });
    }
    // two item carousel

    // three item carousel
    if ($(".three-item-carousel").length) {
        var threeItemCarousel = new Swiper(".three-item-carousel", {
            preloadImages: false,
            loop: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 3,
            speed: 1400,
            spaceBetween: 20,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: false,
            pagination: {
                el: ".slider__pagination2",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next3",
                prevEl: ".swiper-button-prev3",
            },
            breakpoints: {
                1400: { slidesPerView: 3 },
                991: { slidesPerView: 2 },
                640: { slidesPerView: 1 },
            },
        });
    }
    // three item carousel

    // New York
    if ($(".newyork-carousel").length) {
        var threeItemCarousel = new Swiper(".newyork-carousel", {
            preloadImages: false,
            loop: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 3,
            speed: 1400,
            spaceBetween: 20,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: false,
            pagination: {
                el: ".slider__pagination2",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                1400: { slidesPerView: 3 },
                991: { slidesPerView: 2 },
                640: { slidesPerView: 1 },
            },
        });
    }
    // New York

    // four item carousel
    if ($(".four-item-carousel").length) {
        var fourItemCarousel = new Swiper(".four-item-carousel", {
            preloadImages: false,
            loop: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 4,
            speed: 1400,
            spaceBetween: 30,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next2",
                prevEl: ".swiper-button-prev2",
            },
            breakpoints: {
                1400: { slidesPerView: 3 },
                991: { slidesPerView: 2 },
                640: { slidesPerView: 1 },
            },
        });
    }
    // four item carousel

    // five item carousel
    if ($(".five-item-carousel").length) {
        var twoItemCarousel = new Swiper(".five-item-carousel", {
            preloadImages: false,
            loop: true,
            grabCursor: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 5,
            speed: 1400,
            spaceBetween: 30,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".slider-button-next4",
                prevEl: ".slider-button-prev4",
            },
            breakpoints: {
                1400: {
                    slidesPerView: 4,
                },
                991: {
                    slidesPerView: 3,
                },
                640: {
                    slidesPerView: 1,
                },
            },
        });
    }
    // five item carousel

    // six item carousel
    if ($(".six-item-carousel").length) {
        var sixItemCarousel = new Swiper(".six-item-carousel", {
            preloadImages: false,
            loop: true,
            grabCursor: true,
            centeredSlides: false,
            resistance: true,
            resistanceRatio: 0.6,
            slidesPerView: 6,
            speed: 1400,
            spaceBetween: 0,
            parallax: false,
            effect: "slide",
            active: "active",
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".slider__pagination",
                clickable: true,
            },
            navigation: {
                prevEl: ".slider_button_prev6",
                nextEl: ".slider_button_next6",
            },
            breakpoints: {
                1400: {
                    slidesPerView: 4,
                },
                991: {
                    slidesPerView: 2,
                },
                640: {
                    slidesPerView: 1,
                },
            },
        });
    }

    // category swiper caurosel
    if ($(".category-swiper").length) {
        $(".category-swiper").each(function (index, element) {
            const $swiperEl = $(element);
            const $container = $swiperEl.closest(".category-swiper-box");

            const swiperId = "category-swiper-" + index;
            const nextBtn = "category-swiper-button-next-" + index;
            const prevBtn = "category-swiper-button-prev-" + index;

            $swiperEl.addClass(swiperId);
            $container.find(".category-swiper-button-next").addClass(nextBtn);
            $container.find(".category-swiper-button-prev").addClass(prevBtn);

            const totalSlides = $swiperEl.find(".swiper-slide").length;

            const baseSlidesPerView = 5;

            if (totalSlides <= baseSlidesPerView) {
                $container.find(".category-swiper-button-next").hide();
                $container.find(".category-swiper-button-prev").hide();
            }

            const enableLoop = totalSlides > baseSlidesPerView;

            new Swiper("." + swiperId, {
                loop: enableLoop,
                grabCursor: true,
                slidesPerView: baseSlidesPerView,
                spaceBetween: 30,
                navigation: {
                    nextEl: "." + nextBtn,
                    prevEl: "." + prevBtn,
                },
                breakpoints: {
                    1399: { slidesPerView: 4 },
                    1199: { slidesPerView: 3 },
                    991: { slidesPerView: 2 },
                    575: { slidesPerView: 2 },
                    374: { slidesPerView: 1 },
                },
            });
        });
    }

    // categories-nav-slider
    if ($(".categories-nav-slider").length) {
        var swiper = new Swiper(".categories-nav-slider", {
            slidesPerView: 6,
            speed: 1000,
            spaceBetween: 24,
            navigation: {
                nextEl: ".swiper-button-next1",
                prevEl: ".swiper-button-prev1",
            },
            breakpoints: {
                1480: {
                    slidesPerView: 6,
                },
                1440: {
                    slidesPerView: 6,
                },
                1280: {
                    slidesPerView: 5,
                    spaceBetween: 15,
                },
                991: {
                    slidesPerView: 4,
                },
                767: {
                    slidesPerView: 3,
                    spaceBetween: 10,
                },
                575: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
            },
        });
    }

    var swiper = new Swiper(".mySwiper", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    // categories-nav-slider

    if ($(".bannerNewItem-swiper").length) {
        var swiper = new Swiper(".bannerNewItem-swiper", {
            grabCursor: true,
            slidesPerView: 2,
            spaceBetween: 30,
            mousewheel: {
                thresholdDelta: 70,
            },
            loop: true,
            autoplay: {
                delay: 3000,
            },
            breakpoints: {
                1199: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                991: {
                    slidesPerView: 1,
                },
            },
        });
    }

    // owl theme
    if ($(".theme_carousel").length) {
        $(".theme_carousel").each(function (index) {
            var $owlAttr = {},
                $extraAttr = $(this).data("options");
            $.extend($owlAttr, $extraAttr);
            $(this).owlCarousel($owlAttr);
        });
    }
    // owl theme

    // progress (scroll to top) start
    $(".scroll-top-inner").on("click", function () {
        $("html, body").animate({ scrollTop: 0 }, 500);
        return false;
    });

    function handleScrollbar() {
        const bHeight = $("body").height();
        const scrolled = $(window).innerHeight() + $(window).scrollTop();

        let percentage = (scrolled / bHeight) * 100;

        $(".scroll-top-inner .bar-inner").css("width", percentage + "%");
    }

    $(window).on("scroll", function () {
        handleScrollbar();
        if ($(window).scrollTop() > 200) {
            $(".scroll-top-inner").addClass("visible");
        } else {
            $(".scroll-top-inner").removeClass("visible");
        }
    });
    // progress (scroll to top) end

    // Elements Animation
    if ($(".wow").length) {
        var wow = new WOW({
            boxClass: "wow",
            animateClass: "animated",
            offset: 0,
            mobile: true,
            live: true,
        });
        wow.init();
    }
    // Elements Animation

    $(window).on("load", function () {
        //Jquery Curved Circle
        if ($(".curved-circle").length) {
            $(".curved-circle").circleType({
                position: "absolute",
                dir: 1,
                radius: 81,
                forceHeight: true,
                forceWidth: true,
            });
        }
        if ($(".curved-circle-2").length) {
            $(".curved-circle-2").circleType({
                position: "absolute",
                dir: 1,
                radius: 170,
                forceHeight: true,
                forceWidth: true,
            });
        }
    });
    // language currency dropdown

    if ($(".lang-currency-btn").length) {
        const $menu = $(".lang-currency-dropdown-menu");
        const $overlay = $(".lang-currency-overlay");
        const $body = $("body");
        function getScrollbarWidth() {
            return window.innerWidth - document.documentElement.clientWidth;
        }
        $(".lang-currency-btn").on("click", function () {
            $menu.addClass("show");
            $overlay.addClass("show");
            const scrollbarWidth = getScrollbarWidth();
            $body
                .addClass("body-no-scroll")
                .css("padding-right", scrollbarWidth + "px");
        });

        $(".lang-currency-btn-close, .lang-currency-overlay").on(
            "click",
            function () {
                $menu.removeClass("show");
                $overlay.removeClass("show");
                $body.removeClass("body-no-scroll").css("padding-right", "");
            }
        );
    }

    // Hero title gsap split text
    if (document.querySelector(".banner-section-three-title")) {
        gsap.registerPlugin(SplitText);

        // Wait until fonts are loaded
        document.fonts.ready.then(() => {
            let split = new SplitText(".banner-section-three-title", {
                type: "words",
            });

            // reapply gradient on each word
            split.words.forEach((word) => {
                word.style.background =
                    "linear-gradient(180deg, #0f0f10 28.75%, rgba(129,175,185,0.2) 72.5%)";
                word.style.backgroundClip = "text";
                word.style.webkitBackgroundClip = "text";
                word.style.webkitTextFillColor = "transparent";
            });

            // animate words
            gsap.from(split.words, {
                duration: 3,
                y: 200,
                autoAlpha: 0,
                stagger: 0.08,
                ease: "power4.out",
            });
        });
    }

    // gsap split text
    if (document.querySelector(".split-text")) {
        gsap.registerPlugin(SplitText, ScrollTrigger);

        // Wait until fonts are loaded
        document.fonts.ready.then(() => {
            document.querySelectorAll(".split-text").forEach((el) => {
                let split = SplitText.create(el, {
                    type: "lines, words",
                    mask: "lines",
                    autoSplit: true,
                });

                gsap.from(split.words, {
                    duration: 1,
                    y: 100,
                    autoAlpha: 0,
                    stagger: 0.05,
                    ease: "power4.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 80%",
                        toggleActions: "play none none reverse",
                    },
                });
            });
        });
    }

    // Scroll to spread card
    // const featureSection2 = document.querySelector(".feature-section2-inner");
    // if (featureSection2) {
    // gsap.registerPlugin(ScrollTrigger);
    // const item1 = document.querySelector(".feature-box2.box1");
    // const item2 = document.querySelector(".feature-box2.box2");
    // const item3 = document.querySelector(".feature-box2.box3");
    // const item4 = document.querySelector(".feature-box2.box4");

    // const containerWidth = featureSection2.offsetWidth;

    // const item1Width = item1.offsetWidth;
    // const item2Width = item2.offsetWidth;
    // const item3Width = item3.offsetWidth;
    // const item4Width = item4.offsetWidth;

    // const targetX1 = containerWidth / 2 - item1Width / 2;
    // const targetX2 = containerWidth / 2 - item2Width / 2;
    // const targetX3 = containerWidth / 2 - item3Width / 2;
    // const targetX4 = containerWidth / 2 - item4Width / 2;
    // console.log(targetX1);

    // gsap.to(item1, {
    // x: -targetX1,
    // y: -205,
    // duration: 1,
    // ease: "sine.inOut",
    // scrollTrigger: {
    // trigger: featureSection2,
    // start: "top 80%",
    // toggleActions: "play reverse play reverse",
    // },
    // });

    // gsap.to(item2, {
    // x: -180,
    // y: 205,
    // duration: 1,
    // ease: "sine.inOut",
    // scrollTrigger: {
    // trigger: featureSection2,
    // start: "top 100%",
    // toggleActions: "play reverse play reverse",
    // },
    // });

    // gsap.to(item3, {
    // x: 180,
    // y: -205,
    // duration: 1,
    // ease: "sine.inOut",
    // scrollTrigger: {
    // trigger: featureSection2,
    // start: "top 80%",
    // toggleActions: "play reverse play reverse",
    // },
    // });

    // gsap.to(item4, {
    // x: targetX4,
    // y: 205,
    // duration: 1,
    // ease: "sine.inOut",
    // scrollTrigger: {
    // trigger: featureSection2,
    // start: "top 70%",
    // toggleActions: "play reverse play reverse",
    // },
    // });
    // }

    // Scroll to spread card
    window.addEventListener("load", () => {
        const featureSection2 = document.querySelector(
            ".feature-section2-inner"
        );
        if (featureSection2 && window.innerWidth >= 992) {
            gsap.registerPlugin(ScrollTrigger);

            const items = [
                document.querySelector(".feature-box2.box1"),
                document.querySelector(".feature-box2.box2"),
                document.querySelector(".feature-box2.box3"),
                document.querySelector(".feature-box2.box4"),
            ];

            if (items.every((item) => item)) {
                const containerWidth = featureSection2.offsetWidth;
                const containerHeight = featureSection2.offsetHeight;

                const positions = items.map((item, index) => {
                    const itemWidth = item.offsetWidth;
                    const itemHeight = item.offsetHeight;
                    const centerX = containerWidth / 2 - itemWidth / 2;
                    const centerY = containerHeight / 2 - itemHeight / 2;

                    switch (index) {
                        case 0:
                            return { x: -centerX, y: -205 };
                        case 1:
                            return { x: -centerX * 0.35, y: 205 };
                        case 2:
                            return { x: centerX * 0.35, y: -205 };
                        case 3:
                            return { x: centerX, y: 205 };
                        default:
                            return { x: 0, y: 0 };
                    }
                });

                // Set initial position (center)
                items.forEach((item) => {
                    gsap.set(item, { x: 0, y: 0 });
                });

                // Animate on scroll
                items.forEach((item, index) => {
                    gsap.fromTo(
                        item,
                        { x: 0, y: 0 }, // from center
                        {
                            x: positions[index].x,
                            y: positions[index].y,
                            duration: 1,
                            ease: "sine.inOut",
                            scrollTrigger: {
                                trigger: featureSection2,
                                start: "top 80%",
                                toggleActions: "play reverse play reverse",
                            },
                        }
                    );
                });
            }
        }
    });

    // *********************
})(window.jQuery);
