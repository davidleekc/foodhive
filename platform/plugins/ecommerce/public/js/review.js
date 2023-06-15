/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/review.js ***!
  \******************************************************************/
$(function () {
  if ($.rating) {
    $('#rating').rating({
      'size': 'xs'
    });
  }
  function handleError(data) {
    var messages = '';
    if (typeof data.errors !== 'undefined' && !Array.isArray(data.errors)) {
      messages = handleValidationError(data.errors);
    } else {
      if (typeof data.responseJSON !== 'undefined') {
        if (typeof data.responseJSON.errors !== 'undefined') {
          if (data.status === 422) {
            messages = handleValidationError(data.responseJSON.errors);
          }
        } else if (typeof data.responseJSON.message !== 'undefined') {
          messages = data.responseJSON.message;
        } else {
          $.each(data.responseJSON, function (index, el) {
            $.each(el, function (key, item) {
              messages += item + '<br />';
            });
          });
        }
      } else {
        messages = data.statusText;
      }
    }
    return messages;
  }
  function handleValidationError(errors) {
    var message = '';
    $.each(errors, function (index, item) {
      message += item + '<br />';
    });
    return message;
  }
  function submitReviewProduct() {
    var imagesReviewBuffer = [];
    var setImagesFormReview = function setImagesFormReview(input) {
      var dT = new ClipboardEvent('').clipboardData ||
      // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
      new DataTransfer(); // specs compliant (as of March 2018 only Chrome)
      for (var _i = 0, _imagesReviewBuffer = imagesReviewBuffer; _i < _imagesReviewBuffer.length; _i++) {
        var file = _imagesReviewBuffer[_i];
        dT.items.add(file);
      }
      input.files = dT.files;
      loadPreviewImage(input);
    };
    var loadPreviewImage = function loadPreviewImage(input) {
      var $uploadText = $('.ecommerce-image-upload__text');
      var maxFiles = $(input).data('max-files');
      var filesAmount = input.files.length;
      if (maxFiles) {
        if (filesAmount >= maxFiles) {
          $uploadText.closest('.ecommerce-image-upload__uploader-container').addClass('d-none');
        } else {
          $uploadText.closest('.ecommerce-image-upload__uploader-container').removeClass('d-none');
        }
        $uploadText.text(filesAmount + '/' + maxFiles);
      } else {
        $uploadText.text(filesAmount);
      }
      var viewerList = $('.ecommerce-image-viewer__list');
      var $template = $('#ecommerce-review-image-template').html();
      viewerList.addClass('is-loading');
      viewerList.find('.ecommerce-image-viewer__item').remove();
      if (filesAmount) {
        for (var i = filesAmount - 1; i >= 0; i--) {
          viewerList.prepend($template.replace('__id__', i));
        }
        var _loop = function _loop(j) {
          var reader = new FileReader();
          reader.onload = function (event) {
            viewerList.find('.ecommerce-image-viewer__item[data-id=' + j + ']').find('img').attr('src', event.target.result);
          };
          reader.readAsDataURL(input.files[j]);
        };
        for (var j = filesAmount - 1; j >= 0; j--) {
          _loop(j);
        }
      }
      viewerList.removeClass('is-loading');
    };
    $(document).on('change', '.ecommerce-form-review-product input[type=file]', function (event) {
      event.preventDefault();
      var input = this;
      var $input = $(input);
      var maxSize = $input.data('max-size');
      Object.keys(input.files).map(function (i) {
        if (maxSize && input.files[i].size / 1024 > maxSize) {
          var message = $input.data('max-size-message').replace('__attribute__', input.files[i].name).replace('__max__', maxSize);
          MartApp.showError(message);
        } else {
          imagesReviewBuffer.push(input.files[i]);
        }
      });
      var filesAmount = imagesReviewBuffer.length;
      var maxFiles = $input.data('max-files');
      if (maxFiles && filesAmount > maxFiles) {
        imagesReviewBuffer.splice(filesAmount - maxFiles - 1, filesAmount - maxFiles);
      }
      setImagesFormReview(input);
    });
    $(document).on('click', '.ecommerce-form-review-product .ecommerce-image-viewer__icon-remove', function (event) {
      event.preventDefault();
      var $this = $(event.currentTarget);
      var id = $this.closest('.ecommerce-image-viewer__item').data('id');
      imagesReviewBuffer.splice(id, 1);
      var input = $('.ecommerce-form-review-product input[type=file]')[0];
      setImagesFormReview(input);
    });
    $(document).on('submit', '.ecommerce-form-review-product', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var $this = $(e.currentTarget);
      var $button = $this.find('button[type=submit]');
      var productId = $this.find('input[name=product_id]').val();
      $.ajax({
        type: 'POST',
        cache: false,
        url: $this.prop('action'),
        data: new FormData($this[0]),
        contentType: false,
        processData: false,
        beforeSend: function beforeSend() {
          $button.prop('disabled', true).addClass('loading');
          $this.find('.alert-message').removeClass('alert-success').addClass('d-none alert-warning');
        },
        success: function success(res) {
          if (!res.error) {
            $this.find('textarea').val('');
            var $item = $('.ecommerce-product-item[data-id=' + productId + ']');
            $item.find('.ecommerce-product-star').addClass('text-primary h5').html(res.message);
            if ($('#product-review-modal').length) {
              $('#product-review-modal').modal('hide');
            } else {
              $this.find('.alert-message').removeClass('alert-warning d-none').addClass('alert-success').html(res.message);
            }
          } else {
            $this.find('.alert-message').html(res.message).removeClass('d-none');
          }
        },
        error: function error(res) {
          var messages = handleError(res);
          $this.find('.alert-message').html(messages).removeClass('d-none');
        },
        complete: function complete() {
          $button.prop('disabled', false).removeClass('loading');
        }
      });
    });
    $(document).on('click', '.ecommerce-product-star .ecommerce-icon', function (e) {
      var $this = $(e.currentTarget);
      var $product = $this.closest('.ecommerce-product-item');
      var $modal = $('#product-review-modal');
      var $form = $modal.find('form');
      $modal.find('.ecommerce-product-image').attr('src', $product.find('.ecommerce-product-image').attr('src'));
      $modal.find('.ecommerce-product-name').text($product.find('.ecommerce-product-name').text());
      $form.find('input[name=star][value=' + $this.data('star') + ']').prop('checked', true).trigger('change');
      $form.find('input[name=product_id]').val($product.data('id'));
      $modal.modal('show');
    });
    $(document).on('hidden.bs.modal', '#product-review-modal', function (e) {
      var $this = $(e.currentTarget);
      $this.find('.ecommerce-produt-image').attr('src', '');
      $this.find('.ecommerce-produt-name').text('');
      $this.find('input[name=product_id]').val('');
    });
  }
  submitReviewProduct();
});
/******/ })()
;