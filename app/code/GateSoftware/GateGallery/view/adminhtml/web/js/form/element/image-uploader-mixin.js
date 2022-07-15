define(function () {
    'use strict';

    var mixin = {
        defaults: {
            isMultipleFiles: true
        }
    };

    return function (target) {
        return target.extend(mixin);
    }
})
