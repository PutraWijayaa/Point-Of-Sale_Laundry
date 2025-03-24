    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <Script>
$('#id_service').change(function() {
    let id_service = $(this).val();
    $.ajax({
        url: "get-service.php?id_service=" + id_service,
        method: "get",
        dataType: "json",
        success: function(res) {
            $('#service_price').val(res.data.service_price)
        }
    });
});


$('.add-row').click(function() {

    let countDisplay = document.getElementById("countDisplay");
    let currentCount = parseInt(countDisplay.value) || 0;
    currentCount++;
    countDisplay.value = currentCount;

    let service_name = $('#id_service').find("option:selected").text();
    let service_price = $('#service_price').val();

    let newRow = "";

    newRow += "<tr>"
    newRow += `<td> ${currentCount} </td>`;
    newRow += `<td> ${service_name} </td>`;
    newRow += `<td> ${service_price.toLocaleString()} </td>`;
    newRow += "<td><input class='form-control' name='qty[]' type='number'</td>";
    newRow += "<td><input class='form-control' name='notes[]' type='text'</td>";
    newRow +=
        "<td><button type='button' class='btn btn-danger btn-sm remove'><i class='bi bi-trash3-fill'></i></button></td>";
    newRow += "</tr>"

    $('.table-order tbody').append(newRow);

    $('.remove').click(function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    })
});
    </Script>

    </body>

    </html>