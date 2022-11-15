@extends("coreui.layouts.nosidebar-app")

@section("page-title") Plan Page @endsection

@section("page-style")
@endsection

@section("page-content")

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
    integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
table {
    font-size: 0.8vw;
    --cui-table-color: rgba(44, 56, 74, 0.95);
    --cui-table-bg: transparent;
    --cui-table-border-color: #d8dbe0;
    --cui-table-accent-bg: transparent;
    --cui-table-striped-color: rgba(44, 56, 74, 0.95);
    --cui-table-striped-bg: rgba(0, 0, 21, 0.05);
    --cui-table-active-color: rgba(44, 56, 74, 0.95);
    --cui-table-active-bg: rgba(0, 0, 21, 0.1);
    --cui-table-hover-color: rgba(44, 56, 74, 0.95);
    --cui-table-hover-bg: rgba(0, 0, 21, 0.075);
    width: 100%;
    margin-bottom: 1rem;
    color: var(--cui-table-color);
    vertical-align: top;
    border-color: var(--cui-table-border-color);
}

th {
    font-weight: 600;
    text-align: inherit;
    text-align: -webkit-match-parent;
}

table tr th,
table tr td {
    text-align: left;
    padding: 0.5rem 0.3rem;


}

thead,
tbody,
tfoot,
tr,
td,
th {
    border-color: inherit;
    border-style: solid;
    border-width: 0;
}

.table-custom> :not(caption)>*>* {
    padding: 0.2rem 0.2em;
}

.table-custom tr td {

    text-align: left;
    padding: 0.2rem 0.2rem;

}

.table-custom tr td a {
    cursor: pointer;
}

.not-display {
    display: none;
}

.text-del {
    text-decoration: line-through;
}

.lds-dual-ring.hidden {
    display: none;
}

.lds-dual-ring {
    display: inline-block;
    width: 80px;
    height: 80px;
}

.lds-dual-ring:after {
    content: " ";
    display: block;
    width: 64px;
    height: 64px;
    margin: 25% auto;
    border-radius: 50%;
    border: 6px solid #175feb;
    border-color: #175feb transparent #175feb transparent;
    animation: lds-dual-ring 1.2s linear infinite;
}

@keyframes lds-dual-ring {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}


.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgb(197 197 197 / 80%);
    z-index: 999;
    opacity: 1;
    transition: all 0.5s;
}

/*select option:after {
    content: " ";
    height: 5px;
    width: 5px;
    border-radius: 5px;
    display: inline-block;
}

select option.red:after { background: #c00; }
select option.green:after { background: #0c0; }
select option.blue:after { background: #00c; }*/
.square-box {
    height: 18px;
    width: 18px;
    float: right;
}
</style>
<input type="hidden" id="active_sort_column" value="">
<input type="hidden" id="active_sort_by" value="">
<div id="loader" class="lds-dual-ring hidden overlay"></div>
<div class="body flex-grow-1 px-3" style="font-size:12px;">


    <div class="row">
        <div class="col-md-2 col-sm-12 px-3"></div>
        <div class="col-sm-2">

        </div>

        <div class="col-sm-8" id="message">
            @if(session()->has('message.level'))
            <div class="alert alert-{{ session('message.level') }}">
                {!! session('message.content') !!}
            </div>
            @endif
        </div>
        <div class="col-sm-12">

            <div class="plan-list">

            </div>

        </div>
    </div>
    <!--     </div> -->
    @include('pages.crud.popup.add')
    @include('pages.crud.popup.edit-popup')
    @include('pages.crud.popup.edit-order')
    @include('pages.crud.popup.order-highlight-popup')

    @endsection
    @section("page-script")
    <script src="{{url('assets/custom-assets/js/crud/plan.js')}}"></script>
    <!--for date picker-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $(document).on("focus", ".pickercalender", function() {

        $(this).datepicker();

    });

    $(function() {

        $.datepicker.setDefaults({
            dateFormat: "mm/dd/yy",
            onClose: function(date, inst) {

                $(this).val(date);


            }
        });

    });
    $(function($) {

        var scrollbar = $('<div id="fixed-scrollbar"><div></div></div>').appendTo($(document.body));
        scrollbar.hide().css({
            overflowX: 'auto',
            position: 'fixed',
            width: '100%',
            bottom: 0
        });

        var fakecontent = scrollbar.find('div');

        function top(e) {
            return e.offset().top;
        }

        function bottom(e) {
            return e.offset().top + e.height();
        }

        var active = $([]);

        function find_active() {
            scrollbar.show();
            var active = $([]);
            $('.table-responsive').each(function() {
                if (top($(this)) < top(scrollbar) && bottom($(this)) > bottom(scrollbar)) {
                    fakecontent.width($(this).get(0).scrollWidth);
                    fakecontent.height(1);
                    active = $(this);
                }
            });
            fit(active);
            return active;
        }

        function fit(active) {
            if (!active.length) return scrollbar.hide();
            scrollbar.css({
                left: active.offset().left,
                width: active.width()
            });
            fakecontent.width($(this).get(0).scrollWidth);
            fakecontent.height(1);
            delete lastScroll;
        }

        function onscroll() {

            var oldactive = active;
            active = find_active();
            if (oldactive.not(active).length) {
                oldactive.unbind('scroll', update);
            }
            if (active.not(oldactive).length) {
                active.scroll(update);
            }
            update();
        }

        var lastScroll;

        function scroll() {
            if (!active.length) return;
            if (scrollbar.scrollLeft() === lastScroll) return;
            lastScroll = scrollbar.scrollLeft();
            active.scrollLeft(lastScroll);
        }

        function update() {
            if (!active.length) return;
            if (active.scrollLeft() === lastScroll) return;
            lastScroll = active.scrollLeft();
            scrollbar.scrollLeft(lastScroll);
        }

        scrollbar.scroll(scroll);

        onscroll();
        $(window).scroll(onscroll);
        $(window).resize(onscroll);
    });

    document.body.style.zoom = "85%";
    </script>

    <!--for date picker-->
    @endsection