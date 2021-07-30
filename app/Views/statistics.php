<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>

<section class="container">
    <div class="row pt-5 py-lg-5">
        <div class="col-lg-7 col-md-8 mx-auto">

            <div class="card">
                <div class="card-body my-2">
                    <div class="row text-center">
                        <h1 class="fw-light mb-5">Statistics URL</h1>
                        <form action="<?= base_url("generateshorturl") ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="span-url"><?= base_url() . '/' ?></span>
                                <input type="url" aria-describedby="span-url" id="url" name="url" value="<?= $path ?>" class="form-control" autocomplete='off' placeholder="abcDE1234">
                                <button type="button" name="sbm" class="btn btn-outline-secondary" onclick="getStatistics()">Check Click</button>
                            </div>
                        </form>
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
                    <div id="shorten">
                    </div>
                    <hr>
                    <h5 class=" mb-4">รายการจำนวนคลิกล่าสุด 7 วัน</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Click</th>
                                </tr>
                            </thead>
                            <tbody id="listdateclick">
                            </tbody>
                        </table>
                    </div>
                </p>
            </div>
        </div>
    </div>
</section>


<?= $this->section('javascript') ?>
<script>
    var base_url = '<?= base_url() ?>';

    <?= !empty($path) ? 'getStatistics()' : '' ?>

    function getStatistics() {
        var url = $('#url').val();
        if (url.match(/^[\d\w\_\-\&\=\+\#\%\.\?\~/\(\)\@\,\:\!\*]+?$/i)) {
            $.ajax({
                type: "POST",
                url: "<?= base_url("api/statistics") ?>",
                dataType: "json",
                data: {
                    url: url
                },
                success: function(data) {
                    var html = '';

                    $('#shorten').html('<p class="float-end"> <span id="url_qrcode"><img width="100px" src="' + data.url_qrcode + '"></span></p><p><span>URL Full : </span> <span id="url_full"><a href="' + data.url_full + '" target="_blank">' + data.url_full + '</a></span></p><p><span>URL Short : </span> <span id="url_short"><a href="' + data.url_short + '" target="_blank">' + data.url_short + '</a></span></p><p><span>Click Total : </span> <span id="url_click">' + data.url_click + '</span></p><p><span>Create : </span> <span id="url_create_at">' + data.url_create_at + '</span></p>');

                    $.each(data.listdate, function(k, v) {
                        html += '<tr><td>' + v.date + '</td> <td>' + v.click + '</td></tr>';
                    });
                    $('#listdateclick').html(html);
                },
                error: function(xhr, textStatus, errorThrown) {
                    // alert(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        text: 'URL ไม่ถูกต้อง',
                    })

                },
            });
        } else {
            Swal.fire({
                icon: 'error',
                text: 'URL ไม่ถูกต้อง',
            })
        }
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>