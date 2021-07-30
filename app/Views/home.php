<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>

<section class="container">
    <div class="row pt-lg-10 py-lg-5 py-2">
        <div class="col-lg-7 col-md-8 mx-auto">

            <div class="card">
                <div class="card-body my-5">
                    <div class="row text-center">
                        <h1 class="fw-light mb-5">Short URL</h1>
                        <form action="<?= base_url(" generateshorturl") ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="input-group mb-3">
                                <input type="url" id="url" name="url" class="form-control" autocomplete='off' placeholder="ใส่ Url เช่น https://www.google.com">
                                <button type="button" name="sbm" class="btn btn-outline-secondary" onclick="generateShortURL()">Shorten</button>
                            </div>
                        </form>
                    </div>
                    <div id="shorten" class="mt-5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class=" container">
    <div class="row ">
        <div class="col-lg-12 col-md-12 mx-auto">
            <div class="card">
                <div class="card-body ">
                    <h5 class=" mb-4">รายการย่อลิงค์ล่าสุด 5 รายการ</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Full URL</th>
                                    <th>Short URL</th>
                                    <th>QR Code</th>
                                    <th>Click</th>
                                </tr>
                            </thead>
                            <tbody id="shortenlast">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->section('javascript') ?>
<script>
    var base_url = '<?= base_url() ?>';

    function generateShortURL() {

        var url = $("#url").val();
        if (url.match(/^[\d\w\_\-\&\=\+\#\%\.\?\~/\(\)\@\,\:\!\*]+?$/i)) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("api/generateshorturl ") ?>",
                data: {
                    url: url
                },
                dataType: "json",
                success: function(data) {

                    $('#shorten').html('<p><span>URL Full : </span> <span id="url_full"><a href="' + data.url_full + '" target="_blank">' + data.url_full + '</a></span></p><p><span>URL Short : </span> <span id="url_short"><a href="' + data.url_short + '" target="_blank">' + data.url_short + '</a></span></p><p><span>QR CODE : </span> <span id="url_qrcode"><img width="100px" src="' + data.url_qrcode + '"></span></p>');
                    listTop5();
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert(xhr.responseText);
                },
            });
        } else {
            Swal.fire({
                icon: 'error',
                text: 'URL ไม่ถูกต้อง',
            })
        }
    }

    listTop5();

    function listTop5() {
        $.ajax({
            type: "POST",
            url: "<?= base_url("api/listtop") ?>",
            dataType: "json",
            success: function(data) {
                var html = '';
                $.each(data, function(k, v) {
                    html += '<tr><td>' + v.urls_full + '</td> <td><a href="' + v.urls_short_link + '" target="_blank">' + v.urls_short_link + '</a></td>  <td><img width="50px" src="' + v.urls_qrcode + '"></td> <td><a href="' + v.urls_statistics_link + '" target="_blank">' + v.urls_click + '</a> </td></tr>';
                });
                $('#shortenlast').html(html);
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr.responseText);
            },
        });
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>