function agent() {
    var $self = this;;
    this.social_plan = function () {
        var Tweets = new $variable('Tweets');
        var WallPosts = new $variable('WallPosts');
        Tweets.unbind();
        WallPosts.unbind();
        if (true) {
            promise.bind(facebook.get_wall_posts(), function (WallPosts) {
                window.alert(WallPosts.getValue());
                promise.bind(twitter.get_tweets(), function (Tweets) {
                    window.alert(Tweets.getValue());
                    $self.correlate(WallPosts, Tweets);
                });
            })
        } else {
            $error("plan social_plan failed")
        }
    };
    this.foo = function () {;
        if (true) {
            $self.bar()
        } else {
            $error("plan foo failed")
        }
    };
    this.count_words = function (param0) {
        var D = new $variable('D');
        var Ws = new $variable('Ws');
        D.unbind();
        Ws.unbind();
        if ($unify(param0, Ws) && true) {
            $unify(blueprint.lang.dict.make(), D);
            $self.count(Ws, D)
        } else {
            $error("plan count_words failed")
        }
    };
    this.count = function (param0, param1) {
        var C = new $variable('C');
        var D = new $variable('D');
        var W = new $variable('W');
        var Ws = new $variable('Ws');
        C.unbind();
        D.unbind();
        W.unbind();
        Ws.unbind();
        if ($unify(param0, $nil) && $unify(param1, D) && true) {
            console.log(D.getValue())
        } else {
            C.unbind();
            D.unbind();
            W.unbind();
            Ws.unbind();
            if ($unify(param0, new $cons(W, Ws)) && $unify(param1, D) && true) {
                $unify(blueprint.lang.dict.find_default(D.getValue(), W.getValue(), 0), C);
                blueprint.lang.dict.add(D.getValue(), W.getValue(), C.getValue() + 1);
                $self.count(Ws, D)
            } else {
                $error("plan count failed")
            }
        }
    };
    this.correlate = function (param0, param1) {
        var P = new $variable('P');
        var T = new $variable('T');
        P.unbind();
        T.unbind();
        if ($unify(param0, P) && $unify(param1, T) && true) {
            window.alert(P.getValue());
            window.alert(T.getValue())
        } else {
            $error("plan correlate failed")
        }
    };
}
