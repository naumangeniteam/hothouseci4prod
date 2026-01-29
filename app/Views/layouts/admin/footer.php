<script type="text/javascript">
    $(function(){
        <?php if(session()->getFlashdata('alert_error')): ?>
            alertMessageModelPopup('<?php echo session()->getFlashdata('alert_error'); ?>','danger');
            <?php unset($_SESSION['alert_error']); ?>
        <?php elseif(session()->getFlashdata('alert_warning')): ?>
            alertMessageModelPopup('<?php echo session()->getFlashdata('alert_warning'); ?>','warning');
            <?php unset($_SESSION['alert_warning']); ?>
        <?php elseif(session()->getFlashdata('alert_success')): ?>
            alertMessageModelPopup('<?php echo session()->getFlashdata('alert_success'); ?>','success');
            <?php unset($_SESSION['alert_success']); ?>
        <?php elseif(session()->getFlashdata('alert_message')): ?>
            alertMessageModelPopup('<?php echo session()->getFlashdata('alert_message'); ?>','info');
            <?php unset($_SESSION['alert_message']); ?>
        <?php endif; ?>
    });
</script>
<div class="modal fade" id="myViewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header-color-modal bg-color-1">
                <h4 class="modal-title">Modal Header</h4>
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="javascript:void(0);"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <div class="modal-body">
                <p>Loading...</p>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" href="javascript:void(0);">Cancel</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myViewSubDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header-color-modal bg-color-1">
                <h4 class="modal-title">Modal Header</h4>
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="javascript:void(0);"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <div class="modal-body">
                <p>Loading...</p>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" href="javascript:void(0);">Cancel</a>
            </div>
        </div>
    </div>
</div>
<!-- <script type="text/javascript">
    $(document).ready(function(){
        $('table').addClass('table-responsive');
    });
</script> -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let parentDivForTableTopScrollBar = document.createElement(
      "div"
    );
    parentDivForTableTopScrollBar.classList.add(
      "custom-data-table-wrapper1",
      "sticky-top"
    );

    let innerDivForTableTopScrollBar = document.createElement(
      "div"
    );
    innerDivForTableTopScrollBar.classList.add(
      "custom-data-table-top-scrollbar"
    );

    parentDivForTableTopScrollBar.appendChild(
      innerDivForTableTopScrollBar
    );

    // let customDataTableWrapper2 = document.querySelector(
    //   ".custom-data-table-wrapper2"
    // );
    // customDataTableWrapper2.parentNode.insertBefore(
    //   parentDivForTableTopScrollBar,
    //   customDataTableWrapper2
    // );

    // let customDataTableWrapper1 = document.querySelector(
    //   ".custom-data-table-wrapper1"
    // );

    // // Add a scroll event listener to customDataTableWrapper1
    // customDataTableWrapper1.addEventListener(
    //   "scroll",
    //   function() {
    //     customDataTableWrapper2.scrollLeft =
    //       customDataTableWrapper1.scrollLeft;
    //   },
    //   false
    // );

    // // Add a scroll event listener to customDataTableWrapper2
    // customDataTableWrapper2.addEventListener(
    //   "scroll",
    //   function() {
    //     customDataTableWrapper1.scrollLeft =
    //       customDataTableWrapper2.scrollLeft;
    //   },
    //   false
    // );

    // let customDataTable = document.querySelector(
    //   ".custom-data-table"
    // );
    // let customDataTableWidth = customDataTable.offsetWidth;
    // document.querySelector(
    //   ".custom-data-table-top-scrollbar"
    // ).style.width = customDataTableWidth + "px";
  });
</script>