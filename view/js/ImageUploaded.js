(function($){
   $.fn.imageUploaded = function(options, callback)
   {
      if(typeof(options) == "string") {
         if(typeof(callback) == "function")
            options = {action: options, preView: 0, onSuccess: callback}
         else
            options = {action: options, preView: 0}
      }
      if(typeof(options) == "object")
         return new Upload_Img(options, this);
   };

    var Upload_Img = function(options, button)
    {
        this.button = button;
        this.images = $('');
        this.result = '';
        this.form = null;
        this.res_view = '';
        this.form_target = "FormUploadFile";
        this.errorText = {
            cannotImg: "This picture does not exist!",
            cannotAction: "Please change action file!",
            notExistDom: "Not exist DOM",
            notExistInputParams: "Not exist input params!",
            bigSizeFile: "File size exceeds the allowable!",
            imgNotLoad: "The picture is not loaded, try another!",
            returnError: "Error in string return!"
        };
        this.errorInt = {
            cannotImg: 1,
            cannotAction: 2,
            notExistDom: 3,
            notExistInputParams: 4,
            bigSizeFile: 5,
            imgNotLoad: 6,
            returnError: 7
        };
        this.settings = {
            action: '',
            imgPreView: '',
            fileMaxSize: 2*1024*1024, // max size 2MB
            preView: 0,
            onSubmit: function() {},
            onSuccess: function(response){},
            onError: function(Text,Int){}
        };
        // Merge the users options with our defaults
        $.extend(this.settings, options);
        if(this.settings.preView)
            this.images = $(this.settings.imgPreView);

        this.settings.dataType = (this.settings.dataType == 'text' || this.settings.dataType == 'json' ) ? this.settings.dataType : 'text';
        this.settings.preView = (this.settings.preView == 1 || this.settings.preView == 0 ) ? this.settings.preView : 1;

        if(!$('body').size())
        {
            this.settings.onError.call(this, this.errorText.notExistDom, this.errorInt.notExistDom);
        } else if(!this.images.size() && this.settings.preView)
        {
            this.settings.onError.call(this, this.errorText.cannotImg, this.errorInt.cannotImg);
        } else if(this.settings.action == '' || this.settings.action.length < 5)
        {
            this.settings.onError.call(this, this.errorText.cannotAction, this.errorInt.cannotAction);
        } else {
            if($('#hide_input_file').size() == 0)
                $('body').append('<input type="file" name="file" id="hide_input_file" style="display: none;"/>');
            this.clickButton();
            this.changeForm();
        }
   };

   Upload_Img.prototype = {
      clickButton: function()
      {
         var obj = this;
          obj.button.click(function(){
              $('#hide_input_file').click();
              return false;
         });

      },
      changeForm : function()
      {
         var obj = this;
         $('#hide_input_file').change(function() {
            var res = obj.valid_file();
            if(res.back)
            {
                setTimeout(function() {
                    obj.submitForm();
                } , 100);
            } else {
               obj.settings.onError.call(this, res.errorText, res.errorInt);
            }
         });
      },
      submitForm : function() {
          var obj = this;
          obj.settings.onSubmit.call(obj);
          var file_data = $('#hide_input_file').prop('files')[0];
          var form_data = new FormData();
          form_data.append('file', file_data);
          $.ajax({
              url: obj.settings.action,
              type: 'POST',
              dataType: "json",
              data: form_data,
              processData: false,
              contentType: false,
              success: function(data)
              {
                  if (data.status == 1)
                  {
                      obj.settings.onSuccess(obj, data);
                      if(obj.settings.preView == 1) {
                          obj.images.attr('src', data.img);
                      }
                  }
              }
          });
      },
      valid_file: function(){
         var obj = this;
         var _return = { back: 1, errorText: "", errorInt: ""};
         var file_size = $('#hide_input_file')[0].files[0].size;
         if(file_size > obj.settings.fileMaxSize){
             _return.back = 0;
             _return.errorInt = obj.errorInt.bigSizeFile;
             _return.errorText = obj.errorText.bigSizeFile;
         }
         return _return;
      }
   }
})(jQuery);