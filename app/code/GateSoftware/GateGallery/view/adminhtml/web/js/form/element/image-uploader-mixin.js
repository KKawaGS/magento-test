define(function () {
    'use strict';
    var mixin = {
        defaults: {
            isMultipleFiles: true,
        },

        onClickCheck: function ($file) {
            const file = this.getFile((item) => item.id === $file.id);
            const index = this.value.indexOf(file);
            this.value()[index].visibility = !Boolean(Number($file.visibility));
            this.value.replace(file, $file);

            return true;
        },

        isVisible: function ($file) {
            return Boolean(Number($file.visibility));
        }
    };

    return function (target) {
        return target.extend(mixin);
    }
})
