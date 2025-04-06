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

    <!-- Bootstrap JS (jika belum ada) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
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
    let countDisplay = document.getElementById("countDispaly");
    let currentCount = parseInt(countDisplay.value) || 0;
    currentCount++;
    countDisplay.value = currentCount;

    let select = document.getElementById("id_service");
    let selectOpt = select.options[select.selectedIndex];

    let price = parseInt(selectOpt.getAttribute("data-price")) || 0;
    console.log(price);

    let service_name = $('#id_service').find("option:selected").text();
    let service_price = $('#service_price').val();
    let newRow = "";

    newRow += "<tr>"
    newRow += `<td>  ${currentCount}  </td>`;
    newRow +=
        `<td>  ${service_name} <input class='form-control' type='hidden' value='${service_name}' name='service_name[]'>  </td>`;
    newRow +=
        `<td>  ${service_price.toLocaleString()} <input class='form-control' type='hidden' name='subtotal[]' value='${price}' >  </td>`;
    newRow += "<td><input class='form-control' name='qty[]' type='number'></td>";
    newRow += "<td><input class='form-control' name='notes[]' type='text'></td>";
    newRow +=
    "<td><button type='button' class='btn btn-success btn-sm remove' id='remove'>Remove</button></td>";
    newRow += "</tr>"

    $('.table-order tbody').append(newRow);

    $('.remove').click(function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });
});
    </script>

    </body>

    </html>