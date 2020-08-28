
<style>

#photos-show li {
    float: right;
    width: 15%;
    margin-left: 10px;
    list-style-type: none;
}
    
#photos-show {
    height: 405px;
    background-color: #ececec;
    border: 1px solid #9E9E9E;
    padding: 15px;
    }    
    
.placeholder {
	background-color: #333;
	border: 1px dashed #fff;
	height: 180px;
	margin-bottom: 5px;
    
} 
    
</style>

<?php $val = (empty($value) and !Post::get($data,'submit')) ? $field->defaultValue : $fieldData->value ; ?>

          <div class="form-group form-group-<?= $field->name; ?> form-float">

			<div id="div-photos">

				<div class="">

					<ul id="photos-show" class="sortable-list photo-list">
<?php $count = 1; ?>
<?php $array = []; ?>
<?php if(!empty($val)) : ?>
<?php $array = json_decode($val); ?>
<?php endif; ?>
<?php if(empty($val)) : ?>
<?php $array = ''; ?>
<?php endif; ?>

<?php if(!empty($array)) : ?>
<?php foreach($array as $img) : ?>

<li class="sortable-item demo-color-box bg-cyan" id="<?=$count?>"><div><div> <i class="fa fa-trash fa-2x" aria-hidden="true" style="cursor: pointer;" onclick="deletePhoto(<?=$count?>)"></i>  <i class="fa fa-hand-pointer-o fa-2x" style="cursor: pointer;" aria-hidden="true"></i> </div><br /><div><img src="<?=$img->url?>" style="width: 100px;height: 100px;border: 2px solid #333333;margin-left: 10px;" ></div></div></li>

<?php $count++;?>
<?php endforeach; ?>
<?php endif; ?>

					</ul>

				</div>

			</div>

<button type="button" class="btn btn-primary photos-item-index" style="width: 100%" data-toggle="modal" data-target=".bs-example-modal-sm">בחר תמונות</button>

<input name="<?= $field->name; ?>" id="photos-data" type="hidden" value='<?= $val ?>'>

              </div>
         
<script>

    function getItems(div) {
        var columns = [];

        $(div + ' ul.sortable-list').each(function () {
            columns.push($(this).sortable('toArray').join(','));
        });

        return columns.join('|');
    }

    $(document).ready(function () {

        $('#div-photos .sortable-list').sortable({
            update: function (event, ui) {
                setDataPhotos(getItems('#div-photos'));
            },
            placeholder: 'placeholder'
        });

    });

</script>
<script>

    var photos;

    var arr = [];

    var val = "";

</script>
<script>

var count = <?=$count?>;

<?php if(!empty($array)) : ?>
<?php foreach($array as $val) : ?>

arr.push('<?=json_encode($val)?>');

<?php endforeach; ?>
<?php endif; ?>

</script>
<script>

    function setDataPhotos(string) {

        var arrs = string.split(",");
        val = '[';

        for (var i = 0; i < arrs.length; i++) {

            if (i != 0) {
                val += ',' + arr[arrs[i] - 1];
            } else {
                val += arr[arrs[i] - 1];
            }
        }

        val += ']';

        $('#photos-data').val(val);
    }

    function deletePhoto(id) {
        $('#' + id).remove();
        setDataPhotos(getItems('#div-photos'));
    }

    function mediaLibraryGetphotos() {

        photos = mediaLibrarySendItems();

        var html = $('#photos-show').html();

        for (var i = 0; i < photos.length; i++) {

            html += '<li class="sortable-item demo-color-box bg-cyan" id="' + count + '"><div><div> <i class="fa fa-trash fa-2x" aria-hidden="true" style="cursor: pointer;" onclick="deletePhoto(' + count + ')"></i>  <i class="fa fa-hand-pointer-o fa-2x" style="cursor: pointer;" aria-hidden="true"></i> </div><br /><div><img src="' + photos[i].url + '" style="width: 100px;height: 100px;border: 2px solid #333333;margin-left: 10px;" ></div></div></li>';

            arr.push(JSON.stringify(photos[i]));

            count++;
        }

        $('#photos-show').html(html);

        setDataPhotos(getItems('#div-photos'));

        $('#modal-mediaLibrary-id').modal('hide');
    }

    $('.photos-item-index').on('click', function () {

        $('#media-library-block-pop').css('display', 'none');

        $('#media-library-block-file-index').removeClass('col-lg-9');

        $('#media-library-block-file-index').addClass('col-lg-12');

        $('.medialibrary-get-items').attr("onClick", "mediaLibraryGetphotos()");

    });


</script>

</fieldset>