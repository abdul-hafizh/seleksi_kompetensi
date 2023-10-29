<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/app-assets/vendors/css/select2.min.css">

<section>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom pb-2">
            <h4 class="card-title">Pilih Jabatan/Role User</h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <div class="col-xs-12">
              <form id="search_pos">
                <select name="jobtitle" id="pos" class="form-control select2">
                  <option value="">Pilih Jabatan/Role</option>
                  <?php foreach ($jobtitle as $k => $v) { ?>
                    <option <?php echo ($current_jobtitle == $v['job_title']) ? "selected" : "" ?> value="<?php echo $v['job_title'] ?>"><?php echo $v['job_title'] ?></option>
                  <?php } ?>
                </select>
              </form>
            </div>
          </div>
          <br />

          <?php if (!empty($current_jobtitle)) { ?>            
            <div id="treex">
              <?php echo $html ?>
            </div>

            <br />
            <div class="card-body">
              <button type="button" data-loading-text="Loading..." class="save_menu btn btn-info btn-block">Simpan</button>
            </div>
          <?php } ?>
        </div>

      </div>
    </div>
  </div>

  <style type="text/css">
    ul {
      list-style-type: none;
    }
  </style>

  <script src="<?php echo base_url()?>assets/app-assets/vendors/js/select2.full.min.js"></script>
  <script>
      $(document).ready(function () {
          $(".select2").select2({
              dropdownAutoWidth: true,
              width: '100%'
          });
      });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {

      $("#pos").change(function() {
        var val = $(this).find("option:selected").val();
        $("#search_pos").submit();
      });

      $("#treex ul li input:checkbox").click(function() {
        var id = $(this).parent().attr("id");
        var parent = $(this).parent().attr("data-parent");
        var total_child = parseInt($("#treex ul li[data-parent='" + id + "']").length);
        var checked = $(this).prop("checked");

        if (total_child == 0) {

          var p = $("#treex ul li#" + parent + " input:checkbox").prop("checked");

          if (checked && !p) {
            $("#treex ul li#" + parent + " > input:checkbox").prop("checked", true);
            var parent2 = $("#treex ul li#" + parent).attr("data-parent");
            var p2 = $("#treex ul li#" + parent2 + " > input:checkbox").prop("checked");

            if (!p2) {
              $("#treex ul li#" + parent2 + " > input:checkbox").prop("checked", true);
            }

          }

        } else {
          $("#treex ul li[data-parent='" + id + "'] input:checkbox").prop("checked", checked);
        }

      });

      $(".save_menu").click(function() {

        var mydata = [];

        $(".save_menu").prop("disabled", true).button('loading');

        $("input[type='checkbox']:checked").each(function(i, val) {
          var isi = $(this).val();
          mydata.push(isi);
        });

        $.ajax({
          url: "<?php echo site_url('administration/submit_menu_management') ?>",
          data: {
            jobtitle: "<?php echo html_escape($current_jobtitle) ?>",
            menu: mydata
          },
          type: "post",
          success: function(data) {
            alert("Berhasil menyimpan menu");
            $(".save_menu").prop("disabled", false).button('reset');
            window.location.refresh();
          }
        });

      });

    });
  </script>
