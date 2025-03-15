<?php

namespace Guava\Calendar\Enums;

enum Context: string
{
    case DateClick = 'dateClick';
    case DateSelect = 'dateSelect';
    case EventClick = 'eventClick';
    case FreeEventClick = 'freeEventClick';
    case PastFreeEventClick = 'pastFreeEventClick';
    case MyEventClick = 'myEventClick';
    case NoEventsClick = 'noEventsClick';
    case ClosedClick = 'closedClick';
    case MyPastEventClick = 'myPastEventClick';
}
