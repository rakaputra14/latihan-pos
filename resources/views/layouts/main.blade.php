<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $title ?? '' }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <!-- ======= Header ======= -->
    @include('layouts.inc.header');
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('layouts.inc.sidebar')
    <!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>@yield('title')</h1>
            <!-- <h1>{{ $title ?? '' }}</h1> -->
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active">Blank</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        @yield('content')


    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <script>
        // #category_id, document.getElementById('category_id'), document.querySelector('#id')
        let category = document.getElementById('category_id');

        function formatRupiah(number) {
            const formatted = number.toLocaleString("id", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,

            });
            return formatted;
}

        $('#category_id').change(function () {
            let cat_id = $(this).val(),
                option = `<option value="">Select One</option>`;
            $.ajax({
                type: 'GET',
                url: '/get-product/' + cat_id,
                dataType: 'json',
                //alternative if we're using POST method
                //type: 'POST',
                //header: (
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                success: function (resp) {
                    $.each(resp.data, function (index, value) {
                        option += `<option data-price="${value.product_price}" data-img="${value.product_photo}" value="${value.id}">${value.product_name}</option>`;
                    });
                    $('#product_id').html(option);
                }
            });
        });

        $(".add-row").click(function () {
            let tbody = $('tbody');
            let selectedOption = $('#product_id option:selected');
            //let selectedOption = $('#product_id').find('option:selected');
            let namaProduk = selectedOption.text();
            let productid = selectedOption.val();
            let photoProduct = selectedOption.data('img');
            let productPrice = parseInt(selectedOption.data('price')) || 0;

            if ($('#category_id').val() == '')
            {
                alert('Category Required');
                return false;
            }

            if ($('#product_id').val() == '')
            {
                alert('Please select a product');
                return false;
            }

            let newRow = `<tr>`;
            newRow += `<td><img width="100" src="{{ asset('storage/') }}/${photoProduct}" alt="a picture"></td>`
            newRow += `<td>${namaProduk}<input type='hidden' name='product_id[]' value='${productid}'></td>`
            newRow += `<td width='100px'><input min='1' value='1' type='number' name='qty[]' class='qty form-control'></td>`
            newRow += `<td><input type='hidden' name='order_price[]' value='${productPrice}'><span class='price' data-price=${productPrice}>${formatRupiah(productPrice)}</span></td>`
            newRow += `<td><input type='hidden' name='subtotal_input[]' value='${productPrice}'><span class='subtotal'>${formatRupiah(productPrice)}</span></td>`
            // Can Also use Class for input
            // newRow += `<td><input type='hidden' class='subtotal_input[]' value='${productPrice}'><span class='subtotal'>${formatRupiah(productPrice)}</span></td>`
            newRow += `</tr>`;

            tbody.append(newRow);

            calculateSubtotal();

            clearAll();

            $('.qty').off().on('input', function () {
                let qty = parseInt($(this).val()) || 0;
                let price = parseInt($(this).closest('tr').find('.price').data('price')) || 0;
                let subtotal = qty * price;
                $(this).closest('tr').find('.subtotal').text(formatRupiah(subtotal));
                $(this).closest('tr').find('input[name="subtotal_input[]"]').val(subtotal);
                //If using class instead of name use the code below
                //$(this).closest('tr').find('.subtotal_input').val(subtotal);

                calculateSubtotal();
                //Alternative
                //let row = $(this).closest('tr');
                //let price = row.find('.price').data('price')) || 0;
                //.find('.subtotal').text(formatRupiah(subtotal));
            });

            function calculateSubtotal() {
                let grandtotal = 0;
                $('.subtotal').each(function () {
                    let total = parseInt($(this).text().replace(/\./g, '')) || 0;
                    grandtotal += total;
                });
                $('.grandtotal').text(formatRupiah(grandtotal));
                $('input[name="amounttotal"]').val(grandtotal);
            }

        });

        function clearAll() {
            $('#category_id').val('');
            $('#product_id').val('');
        }

        // category.addEventListener('change', function() {
        //     let category_id = this.value;
        //     $.ajax({
        //         type: 'GET',
        //         url: '/get-product/' + category_id,
        //         success: function(data) {
        //             $('#product_id').html(data);
        //         }
        //     });
        // });
    </script>

</body>

</html>
