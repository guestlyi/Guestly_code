<div class="modal fade" id="changeStatusMultiple" tabindex="-1" role="dialog" aria-labelledby="changeStatusMultipleLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="changeStatusMultipleLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="setInactiveRoute" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to change all selected items status?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary change-status-multiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="setRoute">
                @csrf
                @method("delete")
                <div class="modal-body">
                    <p>@lang("Do you want to delete this Badge")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="DeleteMultipleModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMultipleModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeleteMultipleModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to delete all selected data?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary delete-multiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="userModalLabel"><i
                         class="bi bi-check2-square"></i> @lang("Achieved Users")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="" class="badgeManage">
                @csrf
                <div class="model-body">
                    <input type="hidden" name="badgeId" id="badgeId" value="">
                    <input type="hidden" name="selectedUser[]" id="selectedUser" value="">

                    <div class="usersInformation"></div>
                </div>
                <div class="model-footer pt-5 d-flex justify-content-end gap-2 m-3 userModelFooter">
                    <button class="btn btn-sm btn-info badgeUpdate" data-action="inactive" type="button">
                        <i class="bi-dash-circle pe-1"></i>@lang('Remove')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
