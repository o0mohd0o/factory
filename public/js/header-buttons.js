$(document).ready(function () {
    $("#print-qrcode-index").on("click", function (e) {
        e.preventDefault();
        let url = $(this).data("url");
        axios
            .get(url)
            .then((response) => {
                $("#main-content").html(response.data);
            })
            .catch((error) => {
                toastr.error(error.response.data.message);
                let createUrl = $(this).data("create-url");
                axios.get(createUrl).then((response) => {
                    $("#main-content").html(response.data);
                });
            });
    });
    $("#opening-balance").on("click", function (e) {
        e.preventDefault();
        let url = $(this).data("url");
        axios
            .get(url)
            .then((response) => {
                $("#main-content").html(response.data);
            })
            .catch((error) => {
                toastr.error(error.response.data.message);
                let createUrl = $(this).data("create-url");
                axios.get(createUrl).then((response) => {
                    $("#main-content").html(response.data);
                });
            });
    });
    $("#item-cards-index").on("click", function (e) {
        e.preventDefault();
        let url = $(this).data("url");
        axios
            .get(url)
            .then((response) => {
                $("#main-content").html(response.data);
            })
            .catch((error) => {
                toastr.error(error.response.data.message);
            });
    });

    $("#item-card-settings").on("click", function (e) {
        e.preventDefault();
        let url = $(this).data("url");
        axios
            .get(url)
            .then((response) => {
                $("#department-report-show-section").html(response.data);
            })
            .catch((error) => {
                toastr.error(error.response.data.message);
            });
    });
});
