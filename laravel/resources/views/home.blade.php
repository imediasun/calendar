@extends('layouts.calendar')

@section('content')
<div class="container">

    <!-- top -->
    <div id="header">
        <div class="bg-help">
            <div class="inBox">
                <h1 id="logo">Calendar</h1>

                <hr class="hidden" />
            </div>
        </div>
    </div>

    <div id="main">


        <div id="container" >


                    <!-- /top -->


                    <div style="float:left; width: 150px;">
                        <div id="nav"></div>
                    </div>
                    <div style="margin-left: 150px;">
                        <div id="dp"></div>
                    </div>

                    <div id="print"></div>

                    <script type="text/javascript">
                        var nav = new DayPilot.Navigator("nav");
                        nav.showMonths = 3;
                        nav.skipMonths = 3;
                        nav.selectMode = "week";
                        nav.freeHandSelectionEnabled = true;
                        nav.onTimeRangeSelected = function(args) {
                            console.log(args);
                            dp.startDate = args.start;
                            dp.update();
                        };
                        nav.onBeforeCellRender = function(args) {
                            if (args.cell.isCurrentMonth) {
                                args.cell.cssClass = "current-month";
                            }
                        };
                        nav.init();

                        var dp = new DayPilot.Calendar("dp");

                        // view
                        dp.startDate = nav.selectionStart;
                        dp.viewType = "Week";
                        dp.allDayEventHeight = 25;
                        dp.initScrollPos = 9 * 40;
                        dp.moveBy = 'Full';

                        // bubble, with async loading
                        dp.bubble = new DayPilot.Bubble({
                            onLoad: function(args) {
                                var ev = args.source;
                                args.async = true;  // notify manually using .loaded()

                                // simulating slow server-side load
                                setTimeout(function() {
                                    args.html = "testing bubble for: <br>" + ev.text();
                                    args.loaded();
                                }, 500);
                            }
                        });

                        dp.contextMenu = new DayPilot.Menu({
                            items: [
                                {text:"Show event ID", onclick: function() {alert("Event value: " + this.source.value());} },
                                {text:"Show event text", onclick: function() {alert("Event text: " + this.source.text());} },
                                {text:"Show event start", onclick: function() {alert("Event start: " + this.source.start().toStringSortable());} },
                                {text:"Delete", onclick: function() { dp.events.remove(this.source); } }
                            ]});

                        // event moving
                        dp.onEventMoved = function (args) {
                            dp.message("Moved: " + args.e.text());
                        };

                        // event resizing
                        dp.onEventResized = function (args) {
                            dp.message("Resized: " + args.e.text());
                        };

                        // event creating
                        dp.onTimeRangeSelected = function (args) {
                            var name = prompt("New event name:", "Event");
                            if (!name) return;
                            var e = new DayPilot.Event({
                                start: args.start,
                                end: args.end,
                                id: DayPilot.guid(),
                                resource: args.resource,
                                text: "Event"
                            });

                         /*   $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "GET",
                                url: "/AjaxController",
                                data: { name: "John", location: "Boston" }
                            }).done(function( msg ) {
                                alert( "Data Saved: " + msg );
                            });*/

                            dp.events.add(e);
                            dp.clearSelection();
                            dp.message("Created");
                        };

                        dp.onTimeRangeDoubleClicked = function(args) {
                            alert("DoubleClick: start: " + args.start + " end: " + args.end + " resource: " + args.resource);
                        };

                        dp.onEventClick = function(args) {
                            alert("clicked: " + args.e.id());
                        };

                        dp.init();

                        var e = new DayPilot.Event({
                            start: new DayPilot.Date("2013-03-25T12:00:00"),
                            end: new DayPilot.Date("2013-03-25T12:00:00").addHours(3),
                            id: DayPilot.guid(),
                            text: "Special event"
                        });
                        dp.events.add(e);

                    </script>


                    <!-- bottom -->

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var url = window.location.href;
            var filename = url.substring(url.lastIndexOf('/')+1);
            if (filename === "") filename = "index.html";
            $(".menu a[href='" + filename + "']").addClass("selected");
        });

    </script>
    <!-- /bottom -->



</div>
@endsection
