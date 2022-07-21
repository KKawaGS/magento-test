define(function () {
    'use strict';

    var mixin = {
        defaults: {
            isMultipleFiles: true
        },

        onClickCheck: function ($file) {
            $file.visibility = $file.visibility === 'undefined' ? true : !$file.visibility;
        }

    };

    return function (target) {
        return target.extend(mixin);
    }
})
