function $variable(name) {
    var self = this;

    this.name = name;
    this.value = null;

    this.bound = function () {
        return self.value != null;
    };

    this.bind = function (value) {
        if (self.value != null) {
            throw "Variable " + name + " is already bound.";
        }
        while (value instanceof $variable && value.bound()) {
            value = value.getValue();
        }
        self.value = value;
    };

    this.unbind = function () {
        self.value = null;
    }

    this.getValue = function () {
        if (self.value instanceof $variable) {
            return self.value.getValue();
        }
        return self.value;
    }
}

$variable.prototype.toString = function () {
    if (!this.bound()) {
        return "#<" + this.name + ": unbound>";
    }
    return this.value.toString();
}

var $nil = {
    getValue: function () {
        return [];
    }
};

function $cons(head, tail) {
    var self = this;

    this.head = head;
    this.tail = tail;

    this.getValue = function () {
        var res = [];
        var aux = self;
        while (aux != $nil) {
            res.push(aux.head);
            aux = aux.tail;
        }
        return res;
    }
}

$cons.ofArray = function (arr) {
    return arr.reduceRight(function (head, tail) {
        return new $cons(tail, head);
    }, $nil);
}

function $term(tag, name) {
    this.tag = tag;
    this.name = name;

    for (var i = 2; i < arguments.length; i++)
    {
        this["item" + (i - 1)] = arguments[i]
    }
}

$term.prototype.toString = function () {
    return this.name + "()";
}

function $atomic(term) {
    var ty = typeof(term);
    return ty == 'number' || ty == 'string' || term === $nil;
}

function $unify(left, right) {
    if ($atomic(left) && $atomic(right)) {
        return left == right;
    }

    if (left instanceof Array && right == $nil) {
        return left.length == 0;
    }

    if (right instanceof Array && left == $nil) {
        return right.length == 0;
    }

    if (left instanceof Array && right instanceof Array) {
        if (left.length != right.length) {
            return false;
        }

        for (var i = 0; i < left.length; i++) {
            if (!$unify(left[i], right[i])) {
                return false;
            }
        }
    }

    if (left instanceof $cons && right instanceof $cons) {
        return $unify(left.head, right.head) && $unify(left.tail, right.tail);
    }

    if (left instanceof $variable) {
        if (!left.bound()) {
            left.bind(right);
            return true;
        }
        return $unify(left.getValue(), right);
    }

    if (right instanceof $variable) {
        if (!right.bound()) {
            right.bind(left);
            return true;
        }
        return $unify(right.getValue(), left);
    }

    return false;
}

// Dictionary

var blueprint = {
    lang: {
        str: {
            split: function (s) {
                return s.split(' ');
            }
        },
        list: {
            concat: function(l1, l2) {
                if (l1 === $nil) {
                    return l2;
                }
                return l1.concat(l2);
            }
        },
        dict: {
            make: function () {
                return {};
            },
            add: function (d, k, v) {
                d[k] = v;
            },
            find: function (d, k) {
                return d[k];
            },
            find_default: function (d, k, v) {
                var val = d[k];
                return val != undefined ? val : v;
            }
        }
    }
}

// Promise lib

function promiseBuilder() {
    this.unit = function (x) {
        var promise = jQuery.Deferred();
        promise.resolve(x);
        return promise;
    }

    this.bind = function (m, f) {
        return m.then(f);
    }
}

var promise = new promiseBuilder();

// FB

function Facebook() {
    this.statuses = function (v) {
        var promise = jQuery.Deferred();
        FB.api('/me/statuses', function (result) {
            $unify(v, $cons.ofArray(result.data.map(function (status) {
                return status.message ? status.message : "";
            })));
            promise.resolve();
        });
        return promise;
    }
}

// Twitter

function Twitter() {
    this.get_tweets = function (v) {
        var promise = jQuery.Deferred();
        setTimeout(function () {
            $unify(v, "twitter tweets");
            promise.resolve();
        }, 1000);
        return promise;
    }
}

var facebook = new Facebook();
var twitter = new Twitter();
