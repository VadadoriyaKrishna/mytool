
    <div class="container-fluid content-body-part pb-0"> 
        <!-- inner-wrapper -->
        <div id="inner-wrapper">
            <div class="rec-panel card-tab-right d-flex flex-column"> 
                <div class="header-tab-option">  <!--header-tab-option-->
                    <ul class="nav nav-tabs header-tab-menu">
                        <li>
                            <a href="javascript:void(0);" data-add_btn_text="Add CokeyUsers" class="add_btn_li <?php echo ($subTab == 0 || $subTab == "20.26.1") ? 'active' : ''; ?>" onclick="loadData('<?php echo $routeFilePath; ?>?currentPage=20.26.1', 'ajax-cokey-tab', 0);">

                                <i class="fas fa-list"></i>CokeyUsers List
                            </a>
                        </li>
                    </ul>
                    <span class="tab-text-small rx-rigth-btn "> 
                        <a href="javascript:void(0);" class="add_new_data">Add CokeyUsers</a> 
                    </span>
                </div><div class="tab-content right-inner-container" id="ajax-cokey-tab"> 
                <?php
                if ($subTab != 0) {
                    require_once($page[$subTab]);
                } else {
                    require_once($page["20.26.1"]);
                }
                ?> 
            </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on("click", ".add_btn_li", function () {
            $(".add_btn_li").removeClass("active");
            var btn_text = $(this).data("add_btn_text");
            $(".add_new_data").text(btn_text);
            $(this).addClass("active");
        });
    </script>