<?php
/**
*  This file is part of the RPCL project
*
*  Copyright (c) 2004-2011 Embarcadero Technologies, Inc.
*
*  Checkout AUTHORS file for more information on the developers
*
*  This library is free software; you can redistribute it and/or
*  modify it under the terms of the GNU Lesser General Public
*  License as published by the Free Software Foundation; either
*  version 2.1 of the License, or (at your option) any later version.
*
*  This library is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
*  Lesser General Public License for more details.
*
*  You should have received a copy of the GNU Lesser General Public
*  License along with this library; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307
*  USA
*
*/

use_unit("classes.inc.php");
use_unit("graphics.inc.php");
use_unit("css.inc.php");

/**
*
*/
define('alNone','alNone');
define('alTop','alTop');
define('alBottom','alBottom');
define('alLeft','alLeft');
define('alRight','alRight');
define('alClient','alClient');
define('alCustom','alCustom');

define('agNone','agNone');
define('agLeft','agLeft');
define('agCenter','agCenter');
define('agRight','agRight');
define('agInherit','agInherit');

define('crNone','crNone');
define('crPointer','crPointer');
define('crCrossHair','crCrossHair');
define('crText','crText');
define('crWait','crWait');
define('crDefault','crDefault');
define('crHelp','crHelp');
define('crEResize','crE-Resize');
define('crNEResize','crNE-Resize');
define('crNResize','crN-Resize');
define('crNWResize','crNW-Resize');
define('crWResize','crW-Resize');
define('crSWResize','crSW-Resize');
define('crSResize','crS-Resize');
define('crSEResize','crSE-Resize');
define('crAuto','crAuto');
define('crMove','crMove');
define('crProgress','crProgress');



/**
 * Control is the base class for all components that are visible at runtime.
 *
 * Controls are visual components, meaning the user can see them and possibly
 * interact with them at runtime. All controls have properties, methods, and events
 * that describe aspects of their appearance, such as the position of the control,
 * the cursor or hint associated with the control, methods to paint or move the control,
 * and events that respond to user actions.
 *
 * Control has many protected properties and methods that are used or published by its descendants.
 */
class Control extends Component
{
        protected $_caption="";
        protected $_parent=null;
        protected $_controlstyle=array();
        protected $_left=0;
        protected $_visible=1;
        protected $_top=0;
        protected $_width=null;
        protected $_height=null;
        protected $_color="";
        protected $_parentcolor=1;
        protected $_enabled=1;
        protected $_hint="";
        protected $_designcolor="";
        protected $_align=alNone;
        protected $_alignment=agNone;
        protected $_cursor=crNone;
        protected $_showhint=0;
        protected $_parentshowhint=1;

        protected $_font=null;

        protected $_islayer=0;
        protected $_parentfont=1;

        protected $_draggable=0;

        private $_doparentreset = true;

        protected $_jsonactivate=null;
        protected $_jsondeactivate=null;
        protected $_jsonbeforecopy=null;
        protected $_jsonbeforecut=null;
        protected $_jsonbeforedeactivate=null;
        protected $_jsonbeforeeditfocus=null;
        protected $_jsonbeforepaste=null;
        protected $_jsonblur=null;
        protected $_jsonchange=null;
        protected $_jsonclick=null;
        protected $_jsoncontextmenu=null;
        protected $_jsoncontrolselect=null;
        protected $_jsoncopy=null;
        protected $_jsoncut=null;
        protected $_jsondblclick=null;
        protected $_jsondrag=null;
        protected $_jsondragend=null;
        protected $_jsondragenter=null;
        protected $_jsondragleave=null;
        protected $_jsondragover=null;
        protected $_jsondragstart=null;
        protected $_jsondrop=null;
        protected $_jsonfilterchange=null;
        protected $_jsonfocus=null;
        protected $_jsonhelp=null;
        protected $_jsonkeydown=null;
        protected $_jsonkeypress=null;
        protected $_jsonkeyup=null;
        protected $_jsonlosecapture=null;
        protected $_jsonmousedown=null;
        protected $_jsonmouseup=null;
        protected $_jsonmouseenter=null;
        protected $_jsonmouseleave=null;
        protected $_jsonmousemove=null;
        protected $_jsonmouseout=null;
        protected $_jsonmouseover=null;
        protected $_jsonmousewheel=null;
        protected $_jsonscroll=null;
        protected $_jsonpaste=null;
        protected $_jsonpropertychange=null;
        protected $_jsonreadystatechange=null;
        protected $_jsonresize=null;
        protected $_jsonresizeend=null;
        protected $_jsonresizestart=null;
        protected $_jsonselectstart=null;


        /**
        * Fires when the object is set as the active element.
        *
        * This event is fired when the user click an element, other than the active element of the document,
        * or use the keyboard to move focus from the active element to another element.
        * Also can be fired if the script invokes the setActive method on an element,
        * when the element is not the active element.
        *
        * @return mixed
        */
        protected function readjsOnActivate                () { return $this->_jsonactivate; }

        /**
        * Fires when the activeElement is changed from the current object to another object in the parent document.
        *
        * This event is fired when the user click an element, other than the active element of the document,
        * or use the keyboard to move focus from the active element to another element.
        * Also can be fired if the script invokes the setActive method on an element,
        * when the element is not the active element.
        *
        * @return mixed
        */
        protected function readjsOnDeActivate              () { return $this->_jsondeactivate; }

        /**
        * Fires on the source object before the selection is copied to the system clipboard.
        *
        * Is fired if the user right-click to display the shortcut menu and select Copy or
        * presses CTRL+C.
        *
        * @return mixed
        */
        protected function readjsOnBeforeCopy              () { return $this->_jsonbeforecopy; }

        /**
        * Fires on the source object before the selection is deleted from the document.
        *
        * Is fired if the user right-click to display the shortcut menu and select Cut or
        * presses CTRL+X.
        *
        * @return mixed
        */
        protected function readjsOnBeforeCut               () { return $this->_jsonbeforecut; }

        /**
        * Fires immediately before the activeElement is changed from the current object to another object in the parent document.
        *
        * This event is fired when the user click an element, other than the active element of the document,
        * or use the keyboard to move focus from the active element to another element.
        * Also can be fired if the script invokes the setActive method on an element,
        * when the element is not the active element.
        *
        * @return mixed
        */
        protected function readjsOnBeforeDeactivate        () { return $this->_jsonbeforedeactivate; }

        /**
        * Fires before an object contained in an editable element enters a UI-activated state or when an editable
        * container object is control selected.
        *
        * To invoke this event, press the ENTER key or click an object when it has focus or double-click an object.
        * The onbeforeeditfocus event differs from the onfocus event. The onbeforeeditfocus event fires before an
        * object enters a UI-activated state, whereas the onfocus event fires when an object has focus.
        *
        * @return mixed
        */
        protected function readjsOnBeforeEditfocus         () { return $this->_jsonbeforeeditfocus; }

        /**
        * Fires on the target object before the selection is pasted from the system clipboard to the document.
        *
        * Is fired if the user right-click to display the shortcut menu and select Paste or
        * presses CTRL+V.
        *
        * @return mixed
        */
        protected function readjsOnBeforePaste             () { return $this->_jsonbeforepaste; }

        /**
        * Fires when the object loses the input focus.
        *
        * The onblur event fires on the original object before the onfocus or
        * onclick event fires on the object that is receiving focus. Where applicable,
        * the onblur event fires after the onchange event.
        *
        * Use the focus events to determine when to prepare an object to receive
        * or validate input from the user.
        *
        * @return mixed
        */
        protected function readjsOnBlur                    () { return $this->_jsonblur; }

        /**
        * Fires when the contents of the object or selection have changed.
        *
        * This event is fired when the contents are committed and not while the
        * value is changing. For example, on a text box, this event is not fired
        * while the user is typing, but rather when the user commits the change
        * by leaving the text box that has focus. In addition, this event is
        * executed before the code specified by onblur when the control is also
        * losing the focus.
        *
        * @return mixed
        */
        protected function readjsOnChange                  () { return $this->_jsonchange; }

        /**
        * Fires when the user clicks the left mouse button on the object.
        *
        * If the user clicks the left mouse button, the onclick event for an
        * object occurs only if the mouse pointer is over the object and an
        * onmousedown and an onmouseup event occur in that order. For example,
        * if the user clicks the mouse on the object but moves the mouse pointer
        * away from the object before releasing, no onclick event occurs.
        *
        * @return mixed
        */
        protected function readjsOnClick                   () { return $this->_jsonclick; }

        /**
        * Fires when the user clicks the right mouse button in the client area, opening
        * the context menu.
        *
        * @return mixed
        */
        protected function readjsOnContextMenu             () { return $this->_jsoncontextmenu; }

        /**
        * Fires when the user is about to make a control selection of the object.
        *
        * This event fires before the element is selected, so inspecting the
        * selection object gives no information about the element to be selected.
        *
        * @return mixed
        */
        protected function readjsOnControlSelect           () { return $this->_jsoncontrolselect; }

        /**
        * Fires on the source element when the user copies the object or selection, adding it to the system clipboard.
        *
        * Is fired if the user right-click to display the shortcut menu and select Copy or
        * presses CTRL+C.
        *
        * @return mixed
        */
        protected function readjsOnCopy                    () { return $this->_jsoncopy; }

        /**
        * Fires on the source element when the object or selection is removed from the document and added to the system clipboard.
        *
        * Is fired if the user right-click to display the shortcut menu and select Cut or
        * presses CTRL+X.
        *
        * @return mixed
        */
        protected function readjsOnCut                     () { return $this->_jsoncut; }

        /**
        * Fires when the user double-clicks the object.
        *
        * The order of events leading to the ondblclick event is onmousedown,
        * onmouseup, onclick, onmouseup, and then ondblclick.
        *
        * @return mixed
        */
        protected function readjsOnDblClick                () { return $this->_jsondblclick; }

        /**
        * Fires on the source object continuously during a drag operation.
        *
        * This event fires on the source object after the ondragstart event. The
        * ondrag event fires throughout the drag operation, whether the selection
        * being dragged is over the drag source, a valid target, or an invalid target.
        *
        * @return mixed
        */
        protected function readjsOnDrag                    () { return $this->_jsondrag; }

        /**
        * Fires on the source object at the end of a drag operation.
        *
        * @return mixed
        */
        protected function readjsOnDragEnd                    () { return $this->_jsondragend; }

        /**
        * Fires on the target element when the user drags the object to a valid drop target.
        *
        * You can handle the ondragenter event on the source or on the target object.
        * Of the target events, it is the first to fire during a drag operation.
        *
        * @return mixed
        */

        protected function readjsOnDragEnter               () { return $this->_jsondragenter; }

        /**
        * Fires on the target object when the user moves the mouse out of a valid drop target during a drag operation.
        * @return mixed
        */
        protected function readjsOnDragLeave               () { return $this->_jsondragleave; }

        /**
        * Fires on the target element continuously while the user drags the object
        * over a valid drop target.
        *
        * The ondragover event fires on the target object after the ondragenter
        * event has fired.
        *
        * @return mixed
        */
        protected function readjsOnDragOver                () { return $this->_jsondragover; }

        /**
        * Fires on the source object when the user starts to drag a text selection
        * or selected object.
        *
        * The ondragstart event is the first to fire when the user starts to drag
        * the mouse.
        *
        * @return mixed
        */
        protected function readjsOnDragStart               () { return $this->_jsondragstart; }

        /**
        * Fires on the target object when the mouse button is released during a
        * drag-and-drop operation.
        *
        * The ondrop event fires before the ondragleave and ondragend events.
        *
        * @return mixed
        */
        protected function readjsOnDrop                    () { return $this->_jsondrop; }

        /**
        * Fires when a visual filter changes state or completes a transition.
        *
        * @return mixed
        */
        protected function readjsOnFilterChange            () { return $this->_jsonfilterchange; }

        /**
        * Fires when the object receives focus.
        *
        * When one object loses activation and another object becomes the activeElement,
        * the onfocus event fires on the object becoming the activeElement only
        * after the onblur event fires on the object losing activation. Use the
        * focus events to determine when to prepare an object to receive input from
        * the user.
        *
        * @return mixed
        */
        protected function readjsOnFocus                   () { return $this->_jsonfocus; }

        /**
        * Fires when the user presses the F1 key while the browser is the active window.
        * @return mixed
        */
        protected function readjsOnHelp                    () { return $this->_jsonhelp; }

        /**
        * Fires when the user presses a key.
        *
        * This event is specifically fired when the key is pressed down and is repeated
        * multiple times until the key is released.
        *
        * @return mixed
        */
        protected function readjsOnKeyDown                 () { return $this->_jsonkeydown; }

        /**
        * Fires when the user presses an alphanumeric key.
        *
        * This event can be used to detect key presses of standard keys, if you
        * need to process other keys (like cursor keys), use jsOnKeyDown.
        *
        * @return mixed
        */
        protected function readjsOnKeyPress                () { return $this->_jsonkeypress; }

        /**
        * Fires when the user releases a key.
        *
        * This event is fired whenever a key pressed is released, both for keypress and
        * keydown events.
        *
        * @return mixed
        */
        protected function readjsOnKeyUp                   () { return $this->_jsonkeyup; }
        /**
        * Fires when the object loses the mouse capture.
        * @return mixed
        */
        protected function readjsOnLoseCapture             () { return $this->_jsonlosecapture; }

        /**
        * Fires when the user clicks the object with either mouse button.
        *
        * Use this event to detect when the mouse is pressed on an element, you
        * can use the button property of the event to determine which mouse button
        * is clicked.
        *
        * @return mixed
        */
        protected function readjsOnMouseDown               () { return $this->_jsonmousedown; }

        /**
        * Fires when the user releases a mouse button while the mouse is over the object.
        *
        * When any mouse button stops from being pressed over an element, this event is
        * fired, you can use the button property to determine which mouse button
        * is clicked.
        *
        * @return mixed
        */
        protected function readjsOnMouseUp                 () { return $this->_jsonmouseup; }

        /**
        * Fires when the user moves the mouse pointer into the object.
        *
        * The event fires only if the mouse pointer is outside the boundaries of
        * the object and the user moves the mouse pointer inside the boundaries
        * of the object.
        *
        * @return mixed
        */
        protected function readjsOnMouseEnter              () { return $this->_jsonmouseenter; }

        /**
        * Fires when the user moves the mouse pointer outside the boundaries of the object.
        *
        * The event fires only if the mouse pointer is inside the boundaries of
        * the object and the user moves the mouse pointer outside the boundaries
        * of the object.
        *
        * @return mixed
        */
        protected function readjsOnMouseLeave              () { return $this->_jsonmouseleave; }

        /**
        * Fires when the user moves the mouse over the object.
        *
        * If the user presses a mouse button, use the button property to
        * determine which button was pressed.
        *
        * @return mixed
        */
        protected function readjsOnMouseMove               () { return $this->_jsonmousemove; }

        /**
        * Fires when the user moves the mouse pointer outside the boundaries of the object.
        *
        * When the user moves the mouse over an object, one onmouseover event occurs,
        * followed by one or more onmousemove events as the user moves the mouse
        * pointer within the object. One onmouseout event occurs when the user
        * moves the mouse pointer out of the object.
        *
        * @return mixed
        */
        protected function readjsOnMouseOut                () { return $this->_jsonmouseout; }

        /**
        * Fires when the user moves the mouse pointer into the object.
        *
        * The event occurs when the user moves the mouse pointer into the object,
        * and it does not repeat unless the user moves the mouse pointer out of
        * the object and then back into it.
        *
        * @return mixed
        */
        protected function readjsOnMouseOver               () { return $this->_jsonmouseover; }

         /**
        * Fires when the user rotates the mouse wheel over the object.
        *
        * The event is being fired while the user rotates the mouse wheel over the object.
        *
        * @return mixed
        */
        protected function readjsOnMouseWheel               () { return $this->_jsonmousewheel; }

         /**
        * Fires while the user scrolls the object's scrollbar
        *
        * @return mixed
        */
        protected function readjsOnScroll              () { return $this->_jsonscroll; }

        /**
        * Fires on the target object when the user pastes data, transferring the
        * data from the system clipboard to the document.
        *
        * @return mixed
        */
        protected function readjsOnPaste                   () { return $this->_jsonpaste; }

        /**
        * Fires when a property changes on the object.
        *
        * The onpropertychange event fires when properties of an object, expando,
        * or style sub-object change. To retrieve the name of the changed property,
        * use the event object's propertyName property.
        *
        * @return mixed
        */
        protected function readjsOnPropertyChange          () { return $this->_jsonpropertychange; }

        /**
        * Fires when the state of the object has changed.
        *
        * You can use the readyState property to query the current state of the
        * element when the onreadystatechange event fires.
        *
        * @return mixed
        */
        protected function readjsOnReadyStateChange        () { return $this->_jsonreadystatechange; }

        /**
        * Fires when the size of the object is about to change.
        *
        * The onresize event fires for block and inline objects with layout,
        * even if document or CSS (cascading style sheets) property values are changed.
        *
        * @return mixed
        */
        protected function readjsOnResize                  () { return $this->_jsonresize; }

        /**
        * Fires when the user finishes changing the dimensions of the object in
        * a control selection.
        *
        * Only content editable objects can be included in a control selection.
        *
        * @return mixed
        */
        protected function readjsOnResizeEnd               () { return $this->_jsonresizeend; }

        /**
        * Fires when the user begins to change the dimensions of the object in a
        * control selection
        *
        * Only content editable objects can be included in a control selection.
        *
        * @return mixed
        */
        protected function readjsOnResizeStart             () { return $this->_jsonresizestart; }

        /**
        * Fires when the object is being selected
        *
        * The object at the beginning of the selection fires the event.
        *
        * @return mixed
        */
        protected function readjsOnSelectStart             () { return $this->_jsonselectstart; }

        protected function writejsOnActivate($value)                { $this->_jsonactivate=$value; }
        protected function writejsOnDeActivate($value)              { $this->_jsondeactivate=$value; }
        protected function writejsOnBeforeCopy($value)              { $this->_jsonbeforecopy=$value; }
        protected function writejsOnBeforeCut($value)               { $this->_jsonbeforecut=$value; }
        protected function writejsOnBeforeDeactivate($value)        { $this->_jsonbeforedeactivate=$value; }
        protected function writejsOnBeforeEditfocus($value)         { $this->_jsonbeforeeditfocus=$value; }
        protected function writejsOnBeforePaste($value)             { $this->_jsonbeforepaste=$value; }
        protected function writejsOnBlur($value)                    { $this->_jsonblur=$value; }
        protected function writejsOnChange($value)                  { $this->_jsonchange=$value; }
        protected function writejsOnClick($value)                   { $this->_jsonclick=$value; }
        protected function writejsOnContextMenu($value)             { $this->_jsoncontextmenu=$value; }
        protected function writejsOnControlSelect($value)           { $this->_jsoncontrolselect=$value; }
        protected function writejsOnCopy($value)                    { $this->_jsoncopy=$value; }
        protected function writejsOnCut($value)                     { $this->_jsoncut=$value; }
        protected function writejsOnDblClick($value)                { $this->_jsondblclick=$value; }
        protected function writejsOnDrag($value)                    { $this->_jsondrag=$value; }
        protected function writejsOnDragEnd($value)                 { $this->_jsondragend=$value; }
        protected function writejsOnDragEnter($value)               { $this->_jsondragenter=$value; }
        protected function writejsOnDragLeave($value)               { $this->_jsondragleave=$value; }
        protected function writejsOnDragOver($value)                { $this->_jsondragover=$value; }
        protected function writejsOnDragStart($value)               { $this->_jsondragstart=$value; }
        protected function writejsOnDrop($value)                    { $this->_jsondrop=$value; }
        protected function writejsOnFilterChange($value)            { $this->_jsonfilterchange=$value; }
        protected function writejsOnFocus($value)                   { $this->_jsonfocus=$value; }
        protected function writejsOnHelp($value)                    { $this->_jsonhelp=$value; }
        protected function writejsOnKeyDown($value)                 { $this->_jsonkeydown=$value; }
        protected function writejsOnKeyPress($value)                { $this->_jsonkeypress=$value; }
        protected function writejsOnKeyUp($value)                   { $this->_jsonkeyup=$value; }
        protected function writejsOnLoseCapture($value)             { $this->_jsonlosecapture=$value; }
        protected function writejsOnMouseDown($value)               { $this->_jsonmousedown=$value; }
        protected function writejsOnMouseUp($value)                 { $this->_jsonmouseup=$value; }
        protected function writejsOnMouseEnter($value)              { $this->_jsonmouseenter=$value; }
        protected function writejsOnMouseLeave($value)              { $this->_jsonmouseleave=$value; }
        protected function writejsOnMouseMove($value)               { $this->_jsonmousemove=$value; }
        protected function writejsOnMouseOut($value)                { $this->_jsonmouseout=$value; }
        protected function writejsOnMouseOver($value)               { $this->_jsonmouseover=$value; }
        protected function writejsOnMouseWheel($value)              { $this->_jsonmousewheel=$value; }
        protected function writejsOnScroll($value)                  { $this->_jsonscroll=$value; }
        protected function writejsOnPaste($value)                   { $this->_jsonpaste=$value; }
        protected function writejsOnPropertyChange($value)          { $this->_jsonpropertychange=$value; }
        protected function writejsOnReadyStateChange($value)        { $this->_jsonreadystatechange=$value; }
        protected function writejsOnResize($value)                  { $this->_jsonresize=$value; }
        protected function writejsOnResizeEnd($value)               { $this->_jsonresizeend=$value; }
        protected function writejsOnResizeStart($value)             { $this->_jsonresizestart=$value; }
        protected function writejsOnSelectStart($value)             { $this->_jsonselectstart=$value; }

        function defaultjsOnActivate                () { return null; }
        function defaultjsOnDeActivate              () { return null; }
        function defaultjsOnBeforeCopy              () { return null; }
        function defaultjsOnBeforeCut               () { return null; }
        function defaultjsOnBeforeDeactivate        () { return null; }
        function defaultjsOnBeforeEditfocus         () { return null; }
        function defaultjsOnBeforePaste             () { return null; }
        function defaultjsOnBlur                    () { return null; }
        function defaultjsOnChange                  () { return null; }
        function defaultjsOnClick                   () { return null; }
        function defaultjsOnContextMenu             () { return null; }
        function defaultjsOnControlSelect           () { return null; }
        function defaultjsOnCopy                    () { return null; }
        function defaultjsOnCut                     () { return null; }
        function defaultjsOnDblClick                () { return null; }
        function defaultjsOnDrag                    () { return null; }
        function defaultjsOnDragEnd                    () { return null; }
        function defaultjsOnDragEnter               () { return null; }
        function defaultjsOnDragLeave               () { return null; }
        function defaultjsOnDragOver                () { return null; }
        function defaultjsOnDragStart               () { return null; }
        function defaultjsOnDrop                    () { return null; }
        function defaultjsOnFilterChange            () { return null; }
        function defaultjsOnFocus                   () { return null; }
        function defaultjsOnHelp                    () { return null; }
        function defaultjsOnKeyDown                 () { return null; }
        function defaultjsOnKeyPress                () { return null; }
        function defaultjsOnKeyUp                   () { return null; }
        function defaultjsOnLoseCapture             () { return null; }
        function defaultjsOnMouseDown               () { return null; }
        function defaultjsOnMouseUp                 () { return null; }
        function defaultjsOnMouseEnter              () { return null; }
        function defaultjsOnMouseLeave              () { return null; }
        function defaultjsOnMouseMove               () { return null; }
        function defaultjsOnMouseOut                () { return null; }
        function defaultjsOnMouseOver               () { return null; }
        function defaultjsOnMouseWheel               () { return null; }
        function defaultjsOnScroll              () { return null; }
        function defaultjsOnPaste                   () { return null; }
        function defaultjsOnPropertyChange          () { return null; }
        function defaultjsOnReadyStateChange        () { return null; }
        function defaultjsOnResize                  () { return null; }
        function defaultjsOnResizeEnd               () { return null; }
        function defaultjsOnResizeStart             () { return null; }
        function defaultjsOnSelectStart             () { return null; }

        protected $_style="";

        /**
        * Whitespace-separated list of HTML classes to be assigned to the control.
        *
        * HTML classes are mainly used to define styles using CSS code. Hence, you can define a
        * style for a class using CSS (either using the StyleSheet component or manually linking
        * a CSS file from the header of a page) and then give this property the name of that class,
        * so its style is applied to the component.
        *
        * For example, if you define the CSS code ".red { color: red; }" and set the Style property
        * of a Label to "red", the text on the label will be displayed in red.
        */
        function readStyle() { return $this->_style; }
        function writeStyle($value) { $this->_style=$value; }
        function defaultStyle() { return ""; }


        protected $_borderradius=null;

        /**
         * Border style of the control.
         *
         * The Border superproperty provides the following subproperties for you to customize the style of the
         * border around the control:
         * - Color. The color of the border.
         * - Style. The type of border to be used. You can used this property, among other things, to disable the
         *   effects of the superproperty (brsDisabled), inherit the value from the control's container (brsInherit)
         *   or define an invisible border (brsNone) that has effect in the way things like the background are
         *   displayed.
         * - Width. The width of the border.
         *
         * It also provides properties to customize the radius of each corner of the border: BottomLeft, BottomRight,
         * TopLeft and TopRight.
         */
        function readBorderRadius() { return $this->_borderradius; }
        function writeBorderRadius($value)
        {
          if (is_object($value))
          {
            $this->_borderradius=$value;
          }
        }
        function defaultBorderRadius() { return null; }

        /**
         * The style of the border around the control.
         *
         * The Border superproperty provides the following subproperties for you to customize the style of the
         * border around the control:
         * - Color. The color of the border.
         * - Style. The type of border to be used. You can used this property, among other things, to disable the
         *   effects of the superproperty (brsDisabled), inherit the value from the control's container (brsInherit)
         *   or define an invisible border (brsNone) that has effect in the way things like the background are
         *   displayed.
         * - Width. The width of the border.
         *
         * It also provides properties to customize the radius of each corner of the border: BottomLeft, BottomRight,
         * TopLeft and TopRight.
         */
        function getBorderRadius() { return $this->readborderradius(); }
        function setBorderRadius($value) { $this->writeborderradius($value); }

        protected $_gradient=null;

        /**
         * A gradient between two colors to be used as the main color of the control.
         *
         * The Style subproperty of the Gradient superproperty determines the way the rest of the subproperties work.
         *
         * If the Style subproperty is given the gsDisabled value, the whole feature is disabled, and the values of
         * the rest of the subproperties will not have any effect at all.
         *
         * If the Style subproperty is enabled (given either gsLinear or gsRadial as value), the value of the Color
         * property of the control will be ignored, and you will be able to use certain subproperties of Gradient to
         * define a gradient with two colors.
         *
         * There are different subproperties for each one of those two colors, prefixed with either Start- or End-.
         * In the descriptions below, the prefix will be omitted to avoid redundancy.
         *
         * If the Style subproperty is given the gsLinear value, the following subproperties will come into play:
         * - Color. The actual color for each color of the gradient.
         * - HorizontalPosition. The position in the horizontal axis where each color starts, 0% being the left side,
         *   and 100% being the right side.
         * - VerticalPosition. The position in the vertical axis where each color starts, 0% being the top side, and
         *   100% being the bottom side.
         *
         * If the Style property is given the gsRadial value, all the subproperties have some effect:
         * - Color. The actual color for each color of the gradient.
         * - HorizontalPosition. The position in the horizontal axis where each color starts, 0% being the left side,
         *   and 100% being the right side.
         * - VerticalPosition. The position in the vertical axis where each color starts, 0% being the top side, and
         *   100% being the bottom side.
         * - Radius. The radius of each color in the gradient.
         */
        function readGradient() { return $this->_gradient; }
        function writeGradient($value) {

          if (is_object($value))
          {
            $this->_gradient=$value;
          }
        }

        function defaultGradient() { return null; }

        /**
         * A gradient between two colors to be used as the main color of the control.
         *
         * The Style subproperty of the Gradient superproperty determines the way the rest of the subproperties work.
         *
         * If the Style subproperty is given the gsDisabled value, the whole feature is disabled, and the values of
         * the rest of the subproperties will not have any effect at all.
         *
         * If the Style subproperty is enabled (given either gsLinear or gsRadial as value), the value of the Color
         * property of the control will be ignored, and you will be able to use certain subproperties of Gradient to
         * define a gradient with two colors.
         *
         * There are different subproperties for each one of those two colors, prefixed with either Start- or End-.
         * In the descriptions below, the prefix will be omitted to avoid redundancy.
         *
         * If the Style subproperty is given the gsLinear value, the following subproperties will come into play:
         * - Color. The color value for each color of the gradient.
         * - HorizontalPosition. The position in the horizontal axis where each color starts, 0% being the left side,
         *   and 100% being the right side.
         * - VerticalPosition. The position in the vertical axis where each color starts, 0% being the top side, and
         *   100% being the bottom side.
         *
         * If the Style property is given the gsRadial value, all the subproperties have some effect:
         * - Color. The color value for each color of the gradient.
         * - HorizontalPosition. The position in the horizontal axis where each color starts, 0% being the left side,
         *   and 100% being the right side.
         * - VerticalPosition. The position in the vertical axis where each color starts, 0% being the top side, and
         *   100% being the bottom side.
         * - Radius. The radius of each color in the gradient.
         */
        function getGradient() { return $this->readGradient(); }
        function setGradient($value) { $this->writeGradient($value); }

        protected $_transform=null;

        /**
         * A series of transformations to be applied to the control.
         *
         * The Style subproperty of the Transform superproperty determines the way the rest of the subproperties
         * work. The following values are available:
         * - tsDisabled. The whole transformation feature is disabled.
         * - tsAll. All the subproperties are applied to the control.
         * - tsRotation. Apply rotation subproperties only (Rotate).
         * - tsScale. Apply scaling subproperties only (ScaleX and ScaleY).
         * - tsSkew. Apply skewing subproperties only (SkewX and SkewY).
         * - tsTranslate. Apply translation subproperties only (TranslateX and TranslateY).
         *
         * The rest of the subproperties of the Transform superproperty are:
         * - Rotate. Rotation angle.
         * - ScaleX. Scaling rate in the horizontal axis. Set to 1.0 to keep the original size.
         * - ScaleX. Scaling rate in the vertical axis. Set to 1.0 to keep the original size.
         * - SkewX. Skewing angle in the horizontal axis.
         * - SkewY. Skewing angle in the vertical axis.
         * - TranslateX. Translation in the horizontal axis. Negative values translate to the top, positive values
         *   to the bottom.
         * - TranslateY. Translation in the vertical axis. Negative values translate to the left, positive values
         *   to the right.
         */
        function readTransform() { return $this->_transform; }
        function writeTransform($value) {

          if (is_object($value))
          {
            $this->_transform=$value;
          }
        }

        function defaultTransform() { return null; }

        /**
         * A series of transformations to be applied to the control.
         *
         * The Style subproperty of the Transform superproperty determines the way the rest of the subproperties
         * work. The following values are available:
         * - tsDisabled. The whole transformation feature is disabled.
         * - tsAll. All the subproperties are applied to the control.
         * - tsRotation. Apply rotation subproperties only (Rotate).
         * - tsScale. Apply scaling subproperties only (ScaleX and ScaleY).
         * - tsSkew. Apply skewing subproperties only (SkewX and SkewY).
         * - tsTranslate. Apply translation subproperties only (TranslateX and TranslateY).
         *
         * The rest of the subproperties of the Transform superproperty are:
         * - Rotate. Rotation angle.
         * - ScaleX. Scaling rate in the horizontal axis. Set to 1.0 to keep the original size.
         * - ScaleX. Scaling rate in the vertical axis. Set to 1.0 to keep the original size.
         * - SkewX. Skewing angle in the horizontal axis.
         * - SkewY. Skewing angle in the vertical axis.
         * - TranslateX. Translation in the horizontal axis. Negative values translate to the top, positive values
         *   to the bottom.
         * - TranslateY. Translation in the vertical axis. Negative values translate to the left, positive values
         *   to the right.
         */
        function getTransform() { return $this->readTransform(); }
        function setTransform($value) { $this->writeTransform($value); }

        protected $_textshadow=null;

        /**
         * A shadow to be drawn for the text in the control.
         *
         * The Style subproperty defines whether the feature is enabled (tssEnabled) or disabled (tssDisabled).
         *
         * The rest of the subproperties of the TextShadow superproperty are:
         * - BlurRadius. Radius for the blur effect on the shadow. Increase this value to increase the blur level.
         * - Color. Color of the shadow.
         * - HorizontalPosition. The position in the horizontal axis for the left side of the shadow. Negative values
         *   translate to the top, positive values to the bottom.
         * - VerticalPosition. The position in the vertical axis for the top side of the shadow. Negative values
         *   translate to the right, positive values to the left.
         */
        function readTextShadow() { return $this->_textshadow; }
        function writeTextShadow($value)
        {

            if (is_object($value))
            {
              $this->_textshadow=$value;
            }
        }

        function defaultTextShadow() { return null; }

        /**
         * A shadow to be drawn for the text in the control.
         *
         * The Style subproperty defines whether the feature is enabled (tssEnabled) or disabled (tssDisabled).
         *
         * The rest of the subproperties of the TextShadow superproperty are:
         * - BlurRadius. Radius for the blur effect on the shadow. Increase this value to increase the blur level.
         * - Color. Color of the shadow.
         * - HorizontalPosition. The position in the horizontal axis for the left side of the shadow. Negative values
         *   translate to the top, positive values to the bottom.
         * - VerticalPosition. The position in the vertical axis for the top side of the shadow. Negative values
         *   translate to the right, positive values to the left.
         */
        function getTextShadow() { return $this->readTextShadow(); }
        function setTextShadow($value) { $this->writeTextShadow($value); }

        protected $_boxshadow=null;

        /**
         * A shadow to be drawn around the control.
         *
         * The Style subproperty defines whether the feature is enabled (bssEnabled) or disabled (bssDisabled).
         *
         * The rest of the subproperties of the BoxShadow superproperty are:
         * - BlurRadius. Radius for the blur effect on the shadow. Increase this value to increase the blur level.
         * - Color. Color of the shadow.
         * - HorizontalPosition. The position in the horizontal axis for the left side of the shadow. Negative values
         *   translate to the top, positive values to the bottom.
         * - Inset. Whether the shadow should be printed inside the control (bsiYes) or outside (bsiNo).
         * - VerticalPosition. The position in the vertical axis for the top side of the shadow. Negative values
         *   translate to the right, positive values to the left.
         */
        function readBoxShadow() { return $this->_boxshadow; }
        function writeBoxShadow($value)
        {

            if (is_object($value))
            {
              $this->_boxshadow=$value;
            }
        }

        function defaultBoxShadow() { return null; }

        /**
         * A shadow to be drawn around the control.
         *
         * The Style subproperty defines whether the feature is enabled (bssEnabled) or disabled (bssDisabled).
         *
         * The rest of the subproperties of the BoxShadow superproperty are:
         * - BlurRadius. Radius for the blur effect on the shadow. Increase this value to increase the blur level.
         * - Color. Color of the shadow.
         * - HorizontalPosition. The position in the horizontal axis for the left side of the shadow. Negative values
         *   translate to the top, positive values to the bottom.
         * - Inset. Whether the shadow should be printed inside the control (bsiYes) or outside (bsiNo).
         * - VerticalPosition. The position in the vertical axis for the top side of the shadow. Negative values
         *   translate to the right, positive values to the left.
         */
        function getBoxShadow() { return $this->readBoxShadow(); }
        function setBoxShadow($value) { $this->writeBoxShadow($value); }


        protected $_animations = array();

        /**
         * Array that animated events, associations between events of the control and animations that can be either
         * added or removed from this or other controls in the page.
         *
         * In each event, you can add, remove or toggle animations. When you add an animation that was not assigned to
         * the control already, or that was previously removed, it gets triggered. Toggle adds the animation if it was
         * not assigned to the control already, or removes it if it was.
         *
         * Note that user agents do not check whether animations are added or removed during the event, but only check
         * if the they have been added or removed once the event has been handled.
         *
         * For example, if during an onClick event you remove and add the same animation, a click will not trigger it if
         * the animation was already assigned (added) to the control. Usually, that means it will work just once, for
         * the first click event. Instead, you can add the animation during the OnMouseDown event, and remove it during
         * the OnMouseUp event, achieving the desired effect.
         *
         * Each item (animated event) of the array is also an array, whose first element is the target event, and any
         * additional item is an action (add, remove or toggle) over a control for certain animations defined in an
         * Animation component.
         *
         * This would be the syntax of the array of animated events:
         * <ul>
         *   <li>Animated event 1. (array)
         *     <ul>
         *       <li>Target event. For example: 'onclick'.</li>
         *       <li>Animation 1. (array)
         *         <ul>
         *           <li>Action. Possible values are: 'add', 'remove' and 'toggle'.</li>
         *           <li>Target control. Name of the target control. For example: 'Button1'. You can use 'this' to refer to the current control (the one you are editing this property for).</li>
         *           <li>Animation component. The name of the Animation component that contains the animation or animations that will be affected by the action. For example: 'Animation1'.</li>
         *           <li>List of animations of the Animation component to be affected by the action. It is an associative array, where keys can be anythings, and values are the Caption of each animation.</li>
         *         </ul>
         *       </li>
         *       <li>Animation 2. (array)</li>
         *       <li>...</li>
         *     </ul>
         *   </li>
         *   <li>Animated event 2. (array)</li>
         *   <li>...</li>
         * </ul>
         */
        function readAnimations() { return $this->_animations; }
        function writeAnimations($value)
        {
            if (is_array($value))
            {
                $this->_animations = $value;
            }
            else
            {
                $this->_animations = (empty($value)) ? array(): array($value);
            }
        }
        function defaultAnimations() { return array(); }

        // Documented in the parent.
        function getAnimations() { return $this->readAnimations(); }
        function setAnimations($value) { $this->writeAnimations($value); }

        protected $_autosize=0;

        /**
         * Whether the control's width and height values must be omitted in the resulting page (true), so the control
         * size gets adjusted automatically.
         *
         * Specially useful when working with templates.
         *
         * @return boolean
         */
        function readAutoSize() { return $this->_autosize; }
        function writeAutoSize($value) { $this->_autosize=$value; }
        function defaultAutoSize() { return 0; }


        protected $_divwrap=1;

        /**
        * Specifies if the control must be wrapped by a div or not
        *
        * Use this property to specify if the control must be wrapped by a div
        * or not. The usage of this property is leave to the component developer discretion.
        *
        * @return boolean
        */
        function readDivWrap() { return $this->_divwrap; }
        function writeDivWrap($value) { $this->_divwrap=$value; }
        function defaultDivWrap() { return 1; }

        protected $_attributes=array();

        /**
         * A property for tag-based controls to allow users to add attributes to the control's start tag.
         *
         * @return array
         */
        function readAttributes() { return $this->_attributes; }
        function writeAttributes($value) { $this->_attributes=$value; }
        function defaultAttributes() { return array(); }

        /**
        * This function renders all the Attributes in a string
        */
        function strAttributes()
        {
           $output="";

           foreach($this->_attributes as $i=>$v)
           {
             //New way to parse a JSON data for validate in HTML5
             $output.="$i=\"$v\" ";
           }

           return $output;
        }

        protected $_nestedattributes=array();

        /**
        * A property to apply extra attributes for the nested tag elements included in the control
        * for controls like RadioGroup, CheckList
        */
        function readNestedAttributes() { return $this->_nestedattributes; }
        function writeNestedAttributes($value) { $this->_nestedattributes=$value; }
        function defaultNestedAttributes() { return array(); }

        /**
        * This function renders all the NestedAttributes in a string
        * from a given index. If no index is assigned then it will render all the attributes
        *
        * @param integer $index The node from where we are going to retrieve the attributes.
        */
        function strNestedAttributes($index=-1)
        {
           $output="";

           if($index > -1 && array_key_exists($index,$this->_nestedattributes))
            $attributes=$this->_nestedattributes[$index];
           else if($index==-1)
            $attributes=$this->_nestedattributes;
           else
            $attributes=array();

           foreach($attributes as $i=>$v)
           {
             $output.="$i=\"$v\" ";
           }

           return $output;
        }

        protected $_hidden=0;

        /**
         * Whether the control should be hidden upon page load (true) or if it should be visible right away instead
         * (false).
         *
         * Hidden controls can be made visible using client-side scripting.
         *
         * @see readVisible()
         *
         * @return boolean
         */
        function readHidden() { return $this->_hidden; }
        function writeHidden($value) { $this->_hidden=$value; }
        function defaultHidden() { return 0; }

        /**
        * Normalizes the css style class name
        *
        * Return the normalized CSS style without the starting dot if any.
        *
        * @see readStyle()
        *
        * @return string
        */
        function readStyleClass()
        {
            if ($this->_style!="")
            {
                $res=$this->_style;
                if ($res[0]=='.') $res=substr($res,1);
                return($res);
            }
            else return("");
        }



        // Documented in the parent.
        function __construct($aowner=null)
        {
                $this->_font=new Font();
                $this->_font->_control=$this;

                $this->_borderradius=new BorderRadius();
                $this->_borderradius->_control=$this;

                $this->_gradient = new Gradient();
                $this->_gradient->_control=$this;

				        $this->_transform = new Transform();
                $this->_transform->_control=$this;

                $this->_textshadow = new TextShadow();
                $this->_textshadow->_control=$this;

                $this->_boxshadow = new BoxShadow();
                $this->_boxshadow->_control=$this;

                //Calls inherited constructor
                parent::__construct($aowner);
        }

        function loaded()
        {
                parent::loaded();
        }

        /**
         * Determines whether a control can be shown or not.
         *
         * A control can be shown if it has no parent and its Visible property is true.
         *
         * If it has a parent:
         *
         * -if parent has Layer handling properties, checks Visible property if Parent can be shown and Layer matches with the Activelayer.
         *
         * -else, checks the visible property and if the parent can be shown.
         *
         * @see getVisible(), readParent()
         *
         * @return boolean True if the control can be shown, false otherwise
         */
        function canShow()
        {
                if ($this->_parent!=null)
                {
                        //TODO: This must check for parents having ActiveLayer property, not only CustomPanel descendants
                        if ($this->_parent->inheritsFrom('CustomPanel'))
                        {
                                return(($this->_visible) && ($this->_parent->canShow()) && ((string)$this->_layer==(string)$this->_parent->ActiveLayer));
                        }
                        else
                        {
                                return(($this->_visible) && ($this->_parent->canShow()));
                        }
                }
                else return($this->_visible);
        }

        /**
         * Returns a string with all assigned javascript events, ready to be added to a control tag.
         *
         * Returns assigned javascript events as attributes for the tag. This function
         * is useful to get the tags to assign javascript events to the right code.
         *
         * @see dumpJsEvents(),
         *
         * @return string
         */
        function readJsEvents()
        {
                $result="";

                if ($this->_jsonactivate!=null)  { $event=$this->_jsonactivate;  $result.=" onactivate=\"return $event(event)\" "; }
                if ($this->_jsondeactivate!=null)  { $event=$this->_jsondeactivate;  $result.=" ondeactivate=\"return $event(event)\" "; }
                if ($this->_jsonbeforecopy!=null)  { $event=$this->_jsonbeforecopy;  $result.=" onbeforecopy=\"return $event(event)\" "; }
                if ($this->_jsonbeforecut!=null)  { $event=$this->_jsonbeforecut;  $result.=" onbeforecut=\"return $event(event)\" "; }
                if ($this->_jsonbeforedeactivate!=null)  { $event=$this->_jsonbeforedeactivate;  $result.=" onbeforedeactivate=\"return $event(event)\" "; }
                if ($this->_jsonbeforeeditfocus!=null)  { $event=$this->_jsonbeforeeditfocus;  $result.=" onbeforeeditfocus=\"return $event(event)\" "; }
                if ($this->_jsonbeforepaste!=null)  { $event=$this->_jsonbeforepaste;  $result.=" onbeforepaste=\"return $event(event)\" "; }
                if ($this->_jsonblur!=null)  { $event=$this->_jsonblur;  $result.=" onblur=\"return $event(event)\" "; }
                if ($this->_jsonchange!=null)  { $event=$this->_jsonchange;  $result.=" onchange=\"return $event(event)\" "; }
                if ($this->_jsonclick!=null)  { $event=$this->_jsonclick;  $result.=" onclick=\"return $event(event)\" "; }
                if ($this->_jsoncontextmenu!=null)  { $event=$this->_jsoncontextmenu;  $result.=" oncontextmenu=\"return $event(event)\" "; }
                if ($this->_jsoncontrolselect!=null)  { $event=$this->_jsoncontrolselect;  $result.=" oncontrolselect=\"return $event(event)\" "; }
                if ($this->_jsoncopy!=null)  { $event=$this->_jsoncopy;  $result.=" oncopy=\"return $event(event)\" "; }
                if ($this->_jsoncut!=null)  { $event=$this->_jsoncut;  $result.=" oncut=\"return $event(event)\" "; }
                if ($this->_jsondblclick!=null)  { $event=$this->_jsondblclick;  $result.=" ondblclick=\"return $event(event)\" "; }
                if ($this->_jsondrag!=null)  { $event=$this->_jsondrag;  $result.=" ondrag=\"return $event(event)\" "; }
                if ($this->_jsondragend!=null)  { $event=$this->_jsondragend;  $result.=" ondragend=\"return $event(event)\" "; }
                if ($this->_jsondragenter!=null)  { $event=$this->_jsondragenter;  $result.=" ondragenter=\"return $event(event)\" "; }
                if ($this->_jsondragleave!=null)  { $event=$this->_jsondragleave;  $result.=" ondragleave=\"return $event(event)\" "; }
                if ($this->_jsondragover!=null)  { $event=$this->_jsondragover;  $result.=" ondragover=\"return $event(event)\" "; }
                if ($this->_jsondragstart!=null)  { $event=$this->_jsondragstart;  $result.=" ondragstart=\"return $event(event)\" "; }
                if ($this->_jsondrop!=null)  { $event=$this->_jsondrop;  $result.=" ondrop=\"return $event(event)\" "; }
                if ($this->_jsonfilterchange!=null)  { $event=$this->_jsonfilterchange;  $result.=" onfilterchange=\"return $event(event)\" "; }
                if ($this->_jsonfocus!=null)  { $event=$this->_jsonfocus;  $result.=" onfocus=\"return $event(event)\" "; }
                if ($this->_jsonhelp!=null)  { $event=$this->_jsonhelp;  $result.=" onhelp=\"return $event(event)\" "; }
                if ($this->_jsonkeydown!=null)  { $event=$this->_jsonkeydown;  $result.=" onkeydown=\"return $event(event)\" "; }
                if ($this->_jsonkeypress!=null)  { $event=$this->_jsonkeypress;  $result.=" onkeypress=\"return $event(event)\" "; }
                if ($this->_jsonkeyup!=null)  { $event=$this->_jsonkeyup;  $result.=" onkeyup=\"return $event(event)\" "; }
                if ($this->_jsonlosecapture!=null)  { $event=$this->_jsonlosecapture;  $result.=" onlosecapture=\"return $event(event)\" "; }
                if ($this->_jsonmousedown!=null)  { $event=$this->_jsonmousedown;  $result.=" onmousedown=\"return $event(event)\" "; }
                if ($this->_jsonmouseup!=null)  { $event=$this->_jsonmouseup;  $result.=" onmouseup=\"return $event(event)\" "; }
                if ($this->_jsonmouseenter!=null)  { $event=$this->_jsonmouseenter;  $result.=" onmouseenter=\"return $event(event)\" "; }
                if ($this->_jsonmouseleave!=null)  { $event=$this->_jsonmouseleave;  $result.=" onmouseleave=\"return $event(event)\" "; }
                if ($this->_jsonmousemove!=null)  { $event=$this->_jsonmousemove;  $result.=" onmousemove=\"return $event(event)\" "; }
                if ($this->_jsonmouseout!=null)  { $event=$this->_jsonmouseout;  $result.=" onmouseout=\"return $event(event)\" "; }
                if ($this->_jsonmouseover!=null)  { $event=$this->_jsonmouseover;  $result.=" onmouseover=\"return $event(event)\" "; }
                if ($this->_jsonpaste!=null)  { $event=$this->_jsonpaste;  $result.=" onpaste=\"return $event(event)\" "; }
                if ($this->_jsonpropertychange!=null)  { $event=$this->_jsonpropertychange;  $result.=" onpropertychange=\"return $event(event)\" "; }
                if ($this->_jsonreadystatechange!=null)  { $event=$this->_jsonreadystatechange;  $result.=" onreadystatechange=\"return $event(event)\" "; }
                if ($this->_jsonresize!=null)  { $event=$this->_jsonresize;  $result.=" onresize=\"return $event(event)\" "; }
                if ($this->_jsonresizeend!=null)  { $event=$this->_jsonresizeend;  $result.=" onresizeend=\"return $event(event)\" "; }
                if ($this->_jsonresizestart!=null)  { $event=$this->_jsonresizestart;  $result.=" onresizestart=\"return $event(event)\" "; }
                if ($this->_jsonselectstart!=null)  { $event=$this->_jsonselectstart;  $result.=" onselectstart=\"return $event(event)\" "; }

                return($result);
        }

        /**
         * Dumps all assigned javascript events code.
         *
         * Dumps Javascript events. This method is called by the Page component
         * to dump in the <head> section of the document all the javascript
         * functions containing the code the user has written. Control class dumps
         * the standard HTML javascript events. You can override it to dump yours.
         *
         * Don't forget to call the parent:: method if you want the standard ones
         * to get dumped.
         *
         * @see dumpJSEvent()
         *
         */
        function dumpJsEvents()
        {
                $this->dumpJSEvent($this->_jsonactivate);
                $this->dumpJSEvent($this->_jsondeactivate);
                $this->dumpJSEvent($this->_jsonbeforecopy);
                $this->dumpJSEvent($this->_jsonbeforecut);
                $this->dumpJSEvent($this->_jsonbeforedeactivate);
                $this->dumpJSEvent($this->_jsonbeforeeditfocus);
                $this->dumpJSEvent($this->_jsonbeforepaste);
                $this->dumpJSEvent($this->_jsonblur);
                $this->dumpJSEvent($this->_jsonchange);
                $this->dumpJSEvent($this->_jsonclick);
                $this->dumpJSEvent($this->_jsoncontextmenu);
                $this->dumpJSEvent($this->_jsoncontrolselect);
                $this->dumpJSEvent($this->_jsoncopy);
                $this->dumpJSEvent($this->_jsoncut);
                $this->dumpJSEvent($this->_jsondblclick);
                $this->dumpJSEvent($this->_jsondrag);
                $this->dumpJSEvent($this->_jsondragend);
                $this->dumpJSEvent($this->_jsondragenter);
                $this->dumpJSEvent($this->_jsondragleave);
                $this->dumpJSEvent($this->_jsondragover);
                $this->dumpJSEvent($this->_jsondragstart);
                $this->dumpJSEvent($this->_jsondrop);
                $this->dumpJSEvent($this->_jsonfilterchange);
                $this->dumpJSEvent($this->_jsonfocus);
                $this->dumpJSEvent($this->_jsonhelp);
                $this->dumpJSEvent($this->_jsonkeydown);
                $this->dumpJSEvent($this->_jsonkeypress);
                $this->dumpJSEvent($this->_jsonkeyup);
                $this->dumpJSEvent($this->_jsonlosecapture);
                $this->dumpJSEvent($this->_jsonmousedown);
                $this->dumpJSEvent($this->_jsonmouseup);
                $this->dumpJSEvent($this->_jsonmouseenter);
                $this->dumpJSEvent($this->_jsonmouseleave);
                $this->dumpJSEvent($this->_jsonmousemove);
                $this->dumpJSEvent($this->_jsonmouseout);
                $this->dumpJSEvent($this->_jsonmouseover);
                $this->dumpJSEvent($this->_jsonmousewheel);
                $this->dumpJSEvent($this->_jsonscroll);
                $this->dumpJSEvent($this->_jsonpaste);
                $this->dumpJSEvent($this->_jsonpropertychange);
                $this->dumpJSEvent($this->_jsonreadystatechange);
                $this->dumpJSEvent($this->_jsonresize);
                $this->dumpJSEvent($this->_jsonresizeend);
                $this->dumpJSEvent($this->_jsonresizestart);
                $this->dumpJSEvent($this->_jsonselectstart);

        }

        // Documented in the parent.
        function dumpJavascript()
        {
            // Print the code to get JavaScript events to work.
            $this->dumpJsEvents();
        }

        // Documented in the parent
        function dumpAdditionalCSS()
        {


              if ( ( ($this->ControlState & csDesigning) != csDesigning) && is_array($this->_animations) && (count($this->_animations) > 0) )
              {

                    $animations = $this->_animations;

                    foreach ($animations as $animation )
                    {

                        $event = $animation[0];

                        $animation_size = count($animation);

                        $chained_animations = array();

                        for ($i=1; $i<$animation_size; $i++)
                        {

                            if (!isset($animation[$i]))
                                break;

                            $actions = $animation[$i];

                            if (count($actions) < 4)
                                break;

                            $subaction                    = $actions[0];
                            $object_to_apply_animation    = $actions[1];
                            $animation_object             = $actions[2];
                            $animations_checked           = $actions[3];


                            foreach ($animations_checked as $key => $value) {
                                        $new_array = array(
                                                           $animation_object,
                                                           $value);

                                        $chained_animations[$subaction][$object_to_apply_animation][] = $new_array;
                                }

                        }


                        foreach ($chained_animations as $chain_animation)
                        {

                                foreach ($chain_animation as $name_component => $action_with_animations)
                                {

                                        $animations_css = array();                                       
                                        $class_name = $this->Name . "_";

                                        $size_animations = count($action_with_animations);

                                        if ($size_animations >= 2)
                                        {
                                                for ($i=0; $i< $size_animations; $i++)
                                                {
                                                        $animation_object = $action_with_animations[$i][0];
                                                        $animation_selected = $action_with_animations[$i][1];

                                                        $items_owner_animations_object = $this->owner->$animation_object->getItems();

                                                        foreach ($items_owner_animations_object as $item_owner_animations_object)
                                                        {

                                                                if ($item_owner_animations_object['Caption'] == $animation_selected)
                                                                {
                                                                        $class_name .= $item_owner_animations_object['Caption'] . "_";

                                                                        $animations_css[] = array(
                                                                                'Animation_object'     => $animation_object,
                                                                                'Caption'               => $item_owner_animations_object['Caption'] ,
                                                                                'Animation Duration'    => $item_owner_animations_object['Animation Duration'],
                                                                                'Iteration Count'       => $item_owner_animations_object['Iteration Count'],
                                                                                'Animation Timing'      => $item_owner_animations_object['Animation Timing'],
                                                                                'Fill Mode'             => $item_owner_animations_object['Fill Mode']);

                                                                        break;


                                                                }

                                                        }


                                                }

                                                $cont_animaciones = count($animations_css);

                                                $final_animation = "";
                                                $delay = 0;

                                                //transition: <transition-property> <transition-duration> <transition-timing-function>  <transition-delay>

                                                $animation_name = $animation_duration = $animation_timing_function = $animation_iteration_count =
                                                $animation_fill_mode = $animation_delay = "";

                                                for ($i=0; $i < $cont_animaciones; $i++)
                                                {                                                        

                                                        $final_animation .= $animations_css[$i]["Animation_object"] . "_" . $animations_css[$i]['Caption'] . "_cssAnimation " .
                                                                                $animations_css[$i]['Animation Duration'] . " " .
                                                                                $animations_css[$i]['Iteration Count'] . " ";

                                                        if ($i != 0)
                                                                $final_animation .= $delay ."s " ;

                                                        $final_animation .= $animations_css[$i]['Animation Timing'] . " " . $animations_css[$i]['Fill Mode'];

                                                        

                                                        if ($i == ($cont_animaciones-1))
                                                                $final_animation .= ";\n";
                                                        elseif ($i < $cont_animaciones)
                                                                $final_animation .= ",";

                                                        $delay += $animations_css[$i]['Animation Duration'] *  $animations_css[$i]['Iteration Count'];                                                        

                                                }                                                

                                                echo AnimationCSS::getStandardAnimation($class_name, $final_animation);                                                

                                        }

                                }

                        }

                    }

              }

        }

        function pagecreate()
        {

              $result = "";

              if (is_array($this->_animations) && (count($this->_animations) > 0) )
              {

                    $animations = $this->_animations;

                    $cont = count($animations);

                    if ($cont==0)
                        return;

                    $result .= " $('#{$this->Name}').on({ \n" ;

                    foreach ($animations as $animation )
                    {

                        $event = $animation[0];

                        $result .= "'" . substr($event, 2) . "' : function() { \n";

                        $animation_size = count($animation);

                        $chained_animations = array();

                        for ($i=1; $i < $animation_size; $i++)
                        {

                                if (!isset($animation[$i]))
                                        break;

                                $actions = $animation[$i];

                                if (count($actions) < 4)
                                        break;

                                $subaction                    = $actions[0];
                                $object_to_apply_animation    = $actions[1];
                                $animation_object             = $actions[2];
                                $animations_checked           = $actions[3];


                                foreach ($animations_checked as $key => $value) {
                                        # code...
                                        $new_array = array(
                                                                $animation_object,
                                                                $value);



                                        $chained_animations[$subaction][$object_to_apply_animation][] = $new_array;
                                }
                        }

                        foreach ($chained_animations as $subaction => $chain_animation)
                        {

                                foreach ($chain_animation as $name_component => $action_with_animations)
                                {

                                        $size_animations = count($action_with_animations);

                                        if ($size_animations < 2)
                                        {
                                                $result .= "\t";

                                                $animation_checked = $action_with_animations[0][0] . "_" . $action_with_animations[0][1];

                                                if ($name_component == "this")
                                                        $result .= "$(this)";
                                                else
                                                        $result .= "$('#{$name_component}')";

                                                $result .= ".{$subaction}Class('{$animation_checked}');\n";
                                        }
                                        else
                                        {
                                                //$class_name = "";
                                                $class_name = $this->Name . "_";
                                                for ($k=0; $k< $size_animations; $k++)
                                                {

                                                        $animation_selected = $action_with_animations[$k][1];

                                                        $class_name .= $animation_selected . "_";

                                                }

                                                if ($name_component == "this")
                                                        $result .= "$(this)";
                                                else
                                                        $result .= "$('#{$name_component}')";

                                                $result .= ".{$subaction}Class('{$class_name}');\n";

                                        }
                                }
                        }

                        $result .= "}";

                        $cont--;

                        if ( $cont > 0 )
                                $result .= ",";

                        $result .= "\n";
                    }

                $result .= "});  \n ";


              }
              if ($result != "")
                return $result;


        }


        // Documented in the parent.
        function dumpHeaderCode()
        {
                parent::dumpHeaderCode();

                // Dumps only the style sheet at design-time if the style sheet is used by the sub-classed control.
                if (($this->ControlState & csDesigning) == csDesigning && isset($this->_controlstyle['csRenderAlso']) && $this->_controlstyle['csRenderAlso'] == 'StyleSheet')
                {
                        if ($this->owner!=null)
                        {
                                $components=$this->owner->components->items;
                                reset($components);
                                while (list($k,$v)=each($components))
                                {
                                        if ($v->inheritsFrom('StyleSheet'))
                                        {
                                            $v->dumpHeaderCode();
                                        }
                                }
                        }
                }
        }

        /**
         * If the control's Hint attribute is not empty, this method returns a string the HTML attribute definition
         * to be printed inside the start tag of the control.
         *
         * The method replaces any special character in the value of the hint property with HTMl entities.
         *
         * For example, if the Hint property has the value "Drag & drop", this method would return:
         *
         * <code>
         * title="Drag &amp; drop"
         * </code>
         *
         * If there is no value for the Hint property, an empty string is returned instead.
         *
         * @see readShowHint()
         *
         * @return string
         */
        protected function HintAttribute()
        {
                $hint = "";
                //TODO: Check here for alt also
                if ($this->_hint != "")
                {
                        $hintspecial = htmlspecialchars($this->_hint, ENT_QUOTES);
                        if ($this->_showhint)
                        {
                                $hint = "title=\"$hintspecial\"";
                        }
                }
                return $hint;
        }


        /**
         * Dumps all children components.
         *
         * This method iterates through all the children list,
         * dumping all of them to the output using the show method.
         *
         * @see readParent()
         *
         */
        function dumpChildren()
        {

        }

        function dumpCSS()
        {
            echo $this->_borderradius->readCSSString();
            echo $this->_gradient->readCSSString();
            echo $this->_transform->readCSSString();
            echo $this->_textshadow->readCSSString();
            echo $this->_boxshadow->readCSSString();
        }

        /**
        * Adds or replaces the JS event attribute with the wrapper.
        *
        * The wrapper is used to notify the PHP script that an event occured. The
        * script then may fire an event itself (for example OnClick of a button).
        *
        * @see getJSWrapperFunctionName(), readJSWrapperSubmitEventValue(), readJSWrapperSubmitEventValue()
        *
        *
        * @param string $events A string that is empty or contains already
        *                       existing JS event-handlers. This string passed
        *                       by reference.
        * @param string $event String representation of the event (ex. $this->_onclick;)
        * @param string $jsEvent String representation of the JS event (ex. $this->_jsonclick;)
        * @param string $jsEventAttr Name of attribute for the JS event (ex. "onclick")
        */
        protected function addJSWrapperToEvents(&$events, $event, $jsEvent, $jsEventAttr)
        {
                if ($event != null)
                {
                        $wrapperEvent = $this->getJSWrapperFunctionName($event);
                        $submitEventValue = $this->readJSWrapperSubmitEventValue($event);
                        $hiddenfield = $this->readJSWrapperHiddenFieldName();
//                        $hiddenfield = ($this->owner != null) ? "document.forms[0].$hiddenfield" : "null";
                        $hiddenfield = ($this->owner != null) ? "$('#$hiddenfield').get(0)" : "null";
                        if ($jsEvent != null)
                        {
                                $events = str_replace("$jsEventAttr=\"return $jsEvent(event)\"",
                                                      "$jsEventAttr=\"return $wrapperEvent(event, $hiddenfield, '$submitEventValue', $jsEvent)\"",
                                                      $events);
                        }
                        else
                        {
                                $events .= " $jsEventAttr=\"return $wrapperEvent(event, $hiddenfield, '$submitEventValue')\" ";
                        }
                }
        }

        /**
        * Gets the function name of a JS event wrapper.
        *
        * This method can be used to
        * generate a normalized function name for a wrapper. Wrappers are often used
        * to make a process before or after a javascript event is fired.
        *
        * @see addJSWrapperToEvents(), readJSWrapperSubmitEventValue(), readJSWrapperSubmitEventValue()
        *
        * @param string $event String representation of the event (ex. $this->_onclick;)
        * @return string Name of the function
        */
        protected function getJSWrapperFunctionName($event)
        {
                $res = ($event != null) ? $event."Wrapper" : "";
                return $res;
        }

        /**
        * JS wrapper function that forwards a JS event to the PHP script by
        * submitting the HTML form.
        *
        * It is the responsiblity of the component to add this function to the
        * <javascript> section in the HTML header. Usually this is done in the
        * dumpJavascript() function of the component.
        *
        *
        * @see addJSWrapperToEvents(), readJSWrapperSubmitEventValue(), readJSWrapperSubmitEventValue()
        *
        * @param string $event String representation of the event (ex. $this->_onclick;)
        * @return string Returns the whole JS wrapper function for the $event.
        */
        protected function getJSWrapperFunction($event)
        {
                $res = "";
                if ($event != null)
                {
                        $funcName = $this->getJSWrapperFunctionName($event);

                        $res .= "function $funcName(event, hiddenfield, submitvalue, wrappedfunc)\n";
                        $res .= "{\n\n";
                        $res .= "var event = event || window.event;\n";

                        $res .= "submit1=true;\n";
                        $res .= "submit2=true;\n";

                        // Calls the user defined JS function first if it exists.
                        $res .= "if (typeof(wrappedfunc) == 'function') submit1=wrappedfunc(event);\n";

                        $res .= "form = hiddenfield.form;\n";

                        // Submits the form.
                        $res .= "if ((form) && (form.onsubmit) && (typeof(form.onsubmit) == 'function')) submit2=form.onsubmit();\n";
                        // Sets the hidden field value so later you will know which event was fired.
                        $res .= "if ((submit1) && (submit2)) { hiddenfield.value = submitvalue; form.submit(); }\n";

                        // Makes sure the event handler of the element does not handle
                        // the JS event again. (This might happen with a submit button.)
                        $res .= "return false;\n";

                        $res .= "\n}\n";
                        $res .= "\n";
                }
                return $res;
        }

        /**
        * Gets the name of the hidden field used to submit the value for the event
        * that was fired.
        *
        * There should be one hidden field for each component that can forward
        * JS events to the PHP script. It is the responsiblity of the component to
        * add this field.
        *
        * @see addJSWrapperToEvents(), readJSWrapperSubmitEventValue(), readJSWrapperSubmitEventValue()
        *
        * @return string Name of the hidden field
        */
        public function readJSWrapperHiddenFieldName()
        {
                return "{$this->_name}SubmitEvent";
        }

        /**
        * Sets the value to the hidden field when the specific JS event was fired and
        * the wrapper function was called.
        *
        * See getJSWrapperFunction()where the value gets set to the hidden field.
        * It is also used in the component to check if the defined $event has been
        * fired on the page. This should be done in the init() function of the
        * component.
        *
        *
        * @see addJSWrapperToEvents(), readJSWrapperSubmitEventValue(), readJSWrapperHiddenFieldName()
        *
        * @param string $event String representation of the event (ex. $this->_onclick;)
        * @return string The value that will be set in the hidden input.
        */
        public function readJSWrapperSubmitEventValue($event)
        {
                return "{$this->_name}_$event";
        }

        /**
        * Performs a parent reset if true.
        * @return boolean
        */
        function readDoParentReset() { return $this->_doparentreset; }

        /**
         * Text string that labels the control, identifying it to the user.
         *
         * Caption property which is defined in the Control class. If used by the
         * control, is in sync with the Name when the control is first dropped
         * on the designer. The usage of this property depends on the component,
         * for example, Caption for Button components is the text inside the button
         * while for Page components, is the title of the HTML document.
         *
         * Note: Controls that display text use either the Caption property or the
         * Text property to specify the text value. Which property is used depends on the
         * type of control. In general, Caption is used for text that appears as a window title
         * or label, while Text is used for text that appears as the content of a control.
         *
         * @return string
         */
        protected function readCaption() { return $this->_caption; }
        protected function writeCaption($value) { $this->_caption=$value; }
        function defaultCaption() { return ""; }

        /**
         * Specifies the main color of the control.
         *
         * Color property, defined in Control class, usually define the main color
         * for the component, it's responsability of the component developer to
         * use the property to generate the appropiate code and reflect the color setting.
         * This property follows the HTML/CSS color specification, for example:
         * #FF0000 -> red color or can also be "red"
         *
         * If a control's ParentColor property is true, then changing the Color
         * property of the control's parent automatically changes the Color property of the
         * control. When the value of the Color property is changed, the control's ParentColor
         * property is automatically set to false.
         *
         * @link http://www.w3.org/TR/REC-CSS2/syndata.html#color-units
         * @see readParentColor()
         * @example ParentProperties/parentproperties.php How parent properties work
         *
         * @return string
         */
        protected function readColor() { return $this->_color;     }
        protected function writeColor($value)
        {
                if ($this->_color!=$value)
                {
                        $this->_color=$value;
                        if (($this->_controlstate & csLoading) != csLoading)
                        {
                                // Updates the children.
                                if ($this->methodExists("updateChildrenColors"))
                                {
                                        $this->updateChildrenColors();
                                }

                                // Checks if the ParentColor property can be reset.
                                if ($this->_doparentreset)
                                {
                                        $this->_parentcolor=0;
                                }
                        }
                }
        }
        function defaultColor() { return "";     }

        /**
         * Returns a string with the CSS code to apply the Color property of the control.
         *
         * For example:
         * <code>
         * background: #00FF44;
         * </code>
         *
         * It includes a line break (\n) at the end of the string.
         *
         * @return string
         */
        function _readColorCSSString()
        {
            if($this->_color != "")
                return "background: $this->_color;\n";
            return "";
        }

        /**
        * Controls whether the control responds to mouse, keyboard, and timer events.
        *
        * Use Enabled to change the availability of the control to the user. To disable a
        * control, set Enabled to false. Disabled controls appear dimmed. If Enabled is false,
        * the control ignores mouse, keyboard, and timer events.
        *
        * To re-enable a control, set Enabled to true. The control is no longer dimmed, and the
        * user can use the control.
        *
        * Disabled controls must not react to user interaction and must show a different color or aspect
        * to specify that to the user. Do not confuse this with ReadOnly properties,
        * as ReadOnly controls may allow the user to copy information. Disabled controls
        * do not allow any operations with it.
        *
        * @see readVisible()
        *
        * @return boolean
        */
        protected function readEnabled() { return $this->_enabled; }
        protected function writeEnabled($value) { $this->_enabled=$value; }
        function defaultEnabled() { return 1; }

        /**
        * Determines where a control looks for its color information.
        *
        * To have a control use the same color as its parent control,
        * set ParentColor to true. If ParentColor is false,
        * the control uses its own Color property.
        *
        * Set ParentColor to true for all controls in order to ensure that all the
        * controls on a form have a uniform appearance. For example, if ParentColor is
        * true for all controls in a form, changing the background color of the form
        * to gray causes all the controls on the form to also have a gray background.
        *
        * When the value of a control's Color property changes, ParentColor becomes false automatically.
        *
        * @see readColor()
        * @example ParentProperties/parentproperties.php How parent properties work
        *
        * @return boolean
        */
        protected function readParentColor() { return $this->_parentcolor; }
        protected function writeParentColor($value)
        {
                if ($this->_parentcolor!=$value && $this->_doparentreset)
                {
                        $this->_parentcolor=$value;
                        // Only changes the color if parentcolor is set to true;
                        // otherwise leaves it as it is.
                        if ($this->_parentcolor == 1)
                        {
                                if ($this->_parent!=null)
                                {
                                        // Sets the color through writeColor() so child controls are also updated.
                                        $this->writeColor($this->_parent->_color);
                                }
                                else
                                {
                                        $this->_color="";
                                }
                                //Set again the value, as writeColor may change it
                                $this->_parentcolor=$value;
                        }
                }
        }
        function defaultParentColor() { return true;     }

        /**
         * Font-related style properties for the control.
         *
         * @see readParentFont()
         * @example ParentProperties/parentproperties.php How parent properties work
         *
         * @return Font
         */
        protected function readFont() { return $this->_font;       }
        protected function writeFont($value)
        {
                if (is_object($value))
                {
                        $this->_font=$value;

                        if (($this->ControlState & csLoading) != csLoading)
                        {
                                // Updates the children.
                                if ($this->methodExists("updateChildrenFonts"))
                                {
                                        $this->updateChildrenFonts();
                                }

                                // Checks if the ParentFont property can be reset.
                                if ($this->_doparentreset)
                                {
                                        $this->_parentfont=0;
                                }
                        }
                }
        }

        /**
         * Whether the control is a visible part of the page (false) or a separated layer, hidden by default.
         *
         * If set to true, the control will be generated wrapped by a div HTML element, and won't be visible when the
         * page is generated. You will need to use client-side code to show it.
         *
         * This is useful for creating hover areas. For example, on a Panel containing other controls, if this
         * property is true, you can write client-side code on the OnMouseOver client-side event of a Button to show
         * that layer, so you get a nice effect.
         *
         * @see readHidden(), readVisible()
         *
         * @return boolean
         */
        protected function readIsLayer() { return $this->_islayer; }
        protected function writeIsLayer($value) { $this->_islayer=$value; }
        function defaultIsLayer() { return 0; }

        /**
         * Whether the control can be dragged (true) or not (false). This property is used when
         * implementing a Drag and Drop feature.
         *
         * @link wiki://Drag_and_Drop
         *
         * @return boolean
         */
        protected function readDraggable() { return $this->_draggable; }
        protected function writeDraggable($value) { $this->_draggable=$value; }
        function defaultDraggable() { return 0; }


        /**
         * Whether the control inherits its font style from its parent (true), or if it uses its own font style instead
         * (true).
         *
         * When the value of the control's Font property changes, this property is set to false automatically.
         *
         * When this property is set to true in a top-level container (a control without a parent), its Font property
         * is inherited from the application object.
         *
         * @see readFont()
         * @example ParentProperties/parentproperties.php How parent properties work
         *
         * @return boolean
         */
        protected function readParentFont() { return $this->_parentfont; }
        protected function writeParentFont($value)
        {
                if ($this->_parentfont!=$value && $this->_doparentreset)
                {
                        $this->_parentfont=$value;

                        // Only changes the font if parentfont is set to true;
                        // otherwise leaves it as it is.
                        if ($this->_parentfont == 1)
                        {
                                if ($this->_parent!=null)
                                {
                                        // Does not allow you to update ParentFont while assigning
                                        // the parent font to this control. Otherwise, the
                                        // Font::modified() function will try to set $this->ParentFont to false
                                        // because the font has changed.
                                        $this->_doparentreset = false;

                                        $this->Parent->Font->assignTo($this->Font);

                                        $this->_parentfont = 1;
                                        $this->_doparentreset = true;
                                }
                        }
                }
        }
        function defaultParentFont() { return 1; }



        //Public properties

        protected $_layer=0;

        /**
        * Determines the layer in which this control is going to be rendered.
        *
        * This property must match with the ActiveLayer property of the parent control
        * for the component to be shown. It allows you to create stacked interfaces and
        * then switch the active stack by changing ActiveLayer.
        *
        * If parent control does not implement ActiveLayer, you do not have to worry about
        * this property.
        *
        * @see FocusControl::readActiveLayer()
        *
        * @return mixed
        */
        function getLayer() { return $this->_layer; }
        function setLayer($value) { $this->_layer=$value; }
        function defaultLayer() { return 0; }

        /**
        * Determines how the control aligns within its container (parent control).
        *
        * In the current implementation, is only used in a few controls. The goal is
        * to replicate the Align model present in VCL for Windows in RPCL.
        *
        * @return enum
        */
        function readAlign() { return $this->_align;     }
        function writeAlign($value) { $this->_align=$value; }
        function defaultAlign() { return alNone;     }

        //TODO: Check if alignment,color and designcolor must be here or not
        /**
        * Specifies the alignment to be used by the control.
        *
        * Note: This property might have a different effect depending on the type of control.
        *
        * For example, if the control is a Label, the alignment affects the text displayed. For a Page, though, this
        * affects the alignment of the content of the page as a whole (the alignment of the text inside the page
        * does not change).
        *
        * @return enum
        */
        function readAlignment() { return $this->_alignment;     }
        function writeAlignment($value) { $this->_alignment=$value; }
        function defaultAlignment() { return agNone;     }


        /**
        * Specifies a color to use by the control at design time.
        *
        * This is a property a component developer can use to simplify the design of controls. The goal is that
        * this property is only used at design-time. For example, Label control uses it to allow
        * you set a background color which will only be visible at design-time, so you can freely
        * set the Font color and still see the contents.
        *
        * @see readColor(), Control::readControlState()
        *
        * @return string
        */
        function readDesignColor() { return $this->_designcolor;     }
        function writeDesignColor($value) { $this->_designcolor=$value; }
        function defaultDesignColor() { return "";     }

        /**
        * Determines whether the control displays a Help Hint
        * when the mouse pointer rests momentarily on the control.
        *
        * The Help Hint is the value of the Hint property, which is displayed in a
        * box just beneath the control. Use ShowHint to determine whether a Help Hint appears for the control.
        *
        * To enable Help Hint for a particular control, the application ShowHint property
        * must be true and either:
        *
        * the controls own ShowHint property must be true, or
        *
        * the controls ParentShowHint property must be true and its parent's ShowHint property must be true.
        *
        * For example, imagine a check box within a group box. If the ShowHint property of the group box is
        * true and the ParentShowHint property of the check box is true, but the ShowHint property of the
        * check box is false, the check box still displays its Help Hint.
        *
        * Changing the ShowHint value automatically sets the ParentShowHint property to false.
        * Also checks the Hint property, which specifies the text to be shown.
        *
        * @see readHint(), readParentShowHint()
        * @example ParentProperties/parentproperties.php How parent properties work
        *
        * @return boolean
        */
        function readShowHint() { return $this->_showhint;     }
        function writeShowHint($value)
        {
                if ($value!=$this->_showhint)
                {
                        $this->_showhint=$value;

                        if (($this->ControlState & csLoading) != csLoading)
                        {
                                // Updates the children.
                                if ($this->methodExists("updateChildrenShowHints"))
                                {
                                        $this->updateChildrenShowHints();
                                }

                                if ($this->_doparentreset)
                                {
                                        $this->_parentshowhint=0;
                                }
                        }
                }

        }
        function defaultShowHint() { return 0;     }

        /**
         * Whether the control inherits the value of its ShowHint property from its parent (true), or if it uses the
         * value of its own ShowHint property (false).
         *
         * When the value of the control's ShowHint property changes, this property is set to false automatically.
         *
         * When this property is set to true in a top-level container (a control without a parent), its ShowHint
         * property is inherited from the application object.
         *
         * @see readShowHint()
         * @example ParentProperties/parentproperties.php How parent properties work
         *
         * @return boolean
         */
        function readParentShowHint() { return $this->_parentshowhint;     }
        function writeParentShowHint($value)
        {
                if ($this->_parentshowhint!=$value && $this->_doparentreset)
                {
                        $this->_parentshowhint=$value;
                        // Only changes the showhint if parentshowhint is set to true;
                        // otherwise leaves it as it is
                        if ($this->_parentshowhint == 1)
                        {
                                if ($this->_parent!=null)
                                {
                                        //$this->_showhint=$this->_parent->_showhint;
                                        $this->writeShowHint($this->_parent->_showhint);

                                        //Set again the value, as writeColor may change it
                                        $this->_parentshowhint=$value;
                                }
                                else
                                {
                                        $this->_showhint=0;
                                }
                        }
                }
        }
        function defaultParentShowHint() { return 1; }

        /**
        * Updates all properties that use the parent property as source.
        *
        * You don't need to call this method, is called by Control to update all properties
        * that have a Parent relative.
        *
        * These include ShowHint, Color and Font.
        *
        * @see readParentFont(), readParentShowHint(), readParentColor()
        * @example ParentProperties/parentproperties.php How parent properties work
        */
        function updateParentProperties()
        {
                if (($this->_controlstate & csLoading) != csLoading)
                {
                    $this->updateParentFont();
                    $this->updateParentColor();
                    $this->updateParentShowHint();
                }
        }

        /**
        * If ParentFont == true the parent's font is assigned to this control.
        *
        * @see readParentFont()
        * @example ParentProperties/parentproperties.php How parent properties work
        */
        function updateParentFont()
        {
                if ($this->_parent!=null)
                {
                        if (is_object($this->_parent))
                        {
                                $this->_doparentreset = false;
                                if ($this->_parentfont)
                                {
                                    $this->_parent->_font->assignTo($this->Font);
                                }
                                $this->_doparentreset = true;
                        }
                }
        }

        /**
        * If ParentColor == true the parent's color is assigned to this control.
        *
        * @see readParentColor()
        * @example ParentProperties/parentproperties.php How parent properties work
        */
        function updateParentColor()
        {
                if ($this->_parent!=null)
                {
                        if (is_object($this->_parent))
                        {
                                $this->_doparentreset = false;
                                if ($this->_parentcolor)
                                {
                                    $this->writeColor($this->_parent->_color);
                                }
                                $this->_doparentreset = true;
                        }
                }
        }

        /**
        * If ParentShowHint == true the parent's showhint is assigned to this control.
        *
        * @see readParentShowHint()
        * @example ParentProperties/parentproperties.php How parent properties work
        */
        function updateParentShowHint()
        {
                if ($this->_parent!=null)
                {
                        if (is_object($this->_parent))
                        {
                                $this->_doparentreset = false;
                                if ($this->_parentshowhint) $this->writeShowHint($this->_parent->_showhint);
                                $this->_doparentreset = true;
                        }
                }
        }


        /**
         * Determines whether the component appears on the browser.
         *
         * This property determines if the control is visible at run-time or not. Use it to
         * hide this control when generating the page. Note: The
         * behaviour can be different than in VCL for Windows. Since the control
         * uses javascript to get rendered, you might not be able to access it using javascript
         * as the code won't be generated.
         *
         * If you want to get a control code on the browser but not being visible, you should use javascript
         * to hide the control.
         *
         * @see readEnabled()
         *
         * @return boolean
         */
        function readVisible() { return $this->_visible; }
        function writeVisible($value) { $this->_visible=$value; }
        function defaultVisible() { return 1; }

        /**
        * Indicates the parent of the control.
        *
        * Use the Parent property to get or set the parent of this control.
        * The parent of a control is the control that contains the control.
        * For example, if an application includes three radio buttons in a group
        * box, the group box is the parent of the three radio buttons, and the
        * radio buttons are the child controls of the group box.
        *
        * To serve as a parent, a control must be an instance of a descendant of FocusControl.
        *
        * When creating a new control at runtime, assign a Parent property value for
        * the new control. Usually, this is a form, panel, group box, or some control
        * that is designed to contain another. Changing the parent of a control moves
        * the control on the browser so that it is displayed within the new parent.
        * When the parent control moves, the child moves with the parent.
        *
        * @see readOwner()
        * @example ParentProperties/parentproperties.php How parent properties work
        *
        * @return FocusControl
        */
        function readParent() { return $this->_parent; }
        function writeParent($value)
        {
                //Removes this component from the previous parent, if any.
                if (is_object($this->_parent)) $this->_parent->controls->remove($this);

                //Store
                $this->_parent=$value;

                //Adds this component to the parent's control list
                if (is_object($this->_parent))
                {
                        $this->_parent->controls->add($this);

                        $this->updateParentProperties();
                }
        }

        /**
         * An array with control settings to be passed to HTML5 Builder to help it handle the rendering.
         *
         * Available settings:
         * - csAcceptsControls. Whether the control can contain other controls inside (true) or not (false).
         * - csDesignEncoding. Character encoding to be used when rendering the component.
         * - csImageContent. Whether the component renders binary data (true) or not (false).
         * - csLeftOffset. Displacement (integer) in pixels from the left to be used for the image of the component.
         * - csRenderAlso. Comma-separated list of class names for components to be rendered too when rendering this component.
         * - csRenderOwner. Whether the owner of the component should be redrawn when redrawing the component (true) or not (false).
         * - csSlowRedraw. Whether the component includes JavaScript or any other feature that takes time to redraw (true) or not (false).
         * - csTemplateOutput. Whether this component produces valid template output (true) or not (false).
         * - csTopOffset. Displacement (integer) in pixels from the top to be used for the image of the component.
         * - csVerySlowRedraw. Variation of csSlowRedraw for heavy components.
         * - csWebEngine. Web engine to be used when rendering the component. You san set it either to ie, the engine of Internet Explorer, or webkit (default).
         *
         * @link wiki://Component_Integration_with_HTML5_Builder
         */
        function readControlStyle() { return($this->_controlstyle); }
        function writeControlStyle($value)
        {
                $pieces=explode("=",$value);
                $this->_controlstyle[$pieces[0]]=$pieces[1];
        }

        //Here update parent-children properties, after all have been read from the session
        function init()
        {
                parent::init();
                        // Updates the parent properties after loading to ensure all properties
                        // were read from the stream and set.
                        // At the moment of writeParent(), the control does not have
                        // the properties initialized because it is the first property set.

                        // Updates all controls that accept controls inside themselves, but does not update the
                        // root (usually Page) control.
                        if ((isset($this->_controlstyle["csAcceptsControls"])) && ($this->_controlstyle["csAcceptsControls"] == "1" && $this->_parent != null))
                        {
                                // Checks if the parent control will not update this container;
                                // if $this->_parentcolor == 1, then it will be updated by the parent
                                // (and also all children) of this control.
                                if ($this->_parentcolor == 0)
                                {
                                        if ($this->methodExists("updateChildrenColors"))
                                        {
                                                // Checks if there are any children that have $this->_parentcolor == 1.
                                                // If there are, updates them.
                                                $this->updateChildrenColors();
                                        }
                                }
                                // // Checks if the ParentColor property can be reset.
                                if ($this->_parentfont == 0)
                                {
                                        if ($this->methodExists("updateChildrenFonts"))
                                        {
                                                $this->updateChildrenFonts();
                                        }
                                }
                                // // Checks if the ParentColor property can be reset.
                                if ($this->_parentshowhint == 0)
                                {
                                        if ($this->methodExists("updateChildrenShowHints"))
                                        {
                                                $this->updateChildrenShowHints();
                                        }
                                }
                        }
                        // Puts the Page (parent == null) at the end of the if-statement because it is called only once.
                        else if ($this->_parent == null && $this->methodExists("updateChildrenParentProperties"))
                        {
                                $this->updateChildrenParentProperties();
                        }
        }


        /**
        * Uses the Left property to determine where the left side of the control
        * begins, or to reposition the left side of the control.
        *
        * If the control is contained in another control, the Left and Top
        * properties are relative to the parent control. If the control is
        * contained directly by the form, the property values are relative to
        * the form. For forms, the value of the Left property is always 0.
        *
        * @see getTop()
        * @return int
        */
        function getLeft() { return $this->_left; }
        function setLeft($value) { $this->_left=$value; }
        function defaultLeft() { return 0; }

        /**
        * Uses Top to locate the top of the control, or reposition the control to
        * a different Y coordinate.
        *
        * The Top property, like the Left property,
        * is the position of the control relative to its container. Thus, if a
        * control is contained in a Panel, the Left and Top properties are
        * relative to the panel. If the control is contained directly by the
        * form, it is relative to the form. For forms, the value of the Top
        * property is always 0
        *
        * @see getLeft()
        * @return int
        */
        function getTop() { return $this->_top; }
        function setTop($value) { $this->_top=$value; }
        function defaultTop() { return 0; }

        /**
         * Horizontal size of the control, in pixels.
         *
         * @see getHeight()
         *
         * @return int
         */
        function getWidth() { return $this->_width; }
        function setWidth($value) { $this->_width=$value; }
        function defaultWidth() { return 0; }

        /**
         * Vertical size of the control, in pixels.
         *
         * @see getWidth()
         *
         * @return int
         */
        function getHeight() { return $this->_height; }
        function setHeight($value) { $this->_height=$value; }
        function defaultHeight() { return 0; }

         /**
         * Defines if the component must skip the designer height and width or not
         *
         * @see _readCSSSize()
         *
         * @return boolean
         */
        function getAutoSize() { return $this->readautosize(); }
        function setAutoSize($value) { $this->writeautosize($value); }

        /**
         * Returns a string with the CSS code to apply the Height and Width properties of the control.
         *
         * For example:
         * <code>
         * width: 150px;
         * height: 130px;
         * </code>
         *
         * It includes a line break (\n) at the end of the string.
         *
         * @return string
         */
        function _readCSSSize()
        {
            $style = "";

            if($this->isFixedSize())
            {
                if($this->_width != "")
                    $style .= "width: {$this->_width}px;\n";

                if($this->_height != "")
                    $style .= "height: {$this->_height}px;\n";
            }

            return $style;
        }

        /**
         * Returns true if the AutoSize property of the control is disabled. In Design mode, it will always return true,
         * regardless of the value of the AutoSize property.
         *
         * @see _readCSSSize()
         *
         * @return boolean
         */
        function isFixedSize()
        {
            return !$this->_autosize || (($this->ControlState & csDesigning) == csDesigning);
        }


        /**
        * Change the value of Cursor to provide feedback to the user when the
        * mouse pointer enters the control.
        *
        * The value of Cursor is one of the available cursors for the browser, in the IDE you have a drop-down
        * list to select a valid value for this property, which can be one of the following:
        *
        * crNone      - Indicate that this property will not be printed in the CSS file
        *
        * crPointer   - The cursor is a pointer that indicates a link.
        *
        * crCrossHair - A simple crosshair (e.g., short line segments resembling a "+" sign).
        *
        * crText      - Indicates text that may be selected. Often rendered as an I-bar.
        *
        * crWait      - Indicates that the program is busy and the user should wait. Often rendered as a watch or hourglass.
        *
        * crDefault   - The platform-dependent default cursor. Often rendered as an arrow.
        *
        * crHelp      - Help is available for the object under the cursor. Often rendered as a question mark or a balloon.
        *
        * crEResize   - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crNEResize  - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crNResize   - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crNWResize  - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crWResize   - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crSWResize  - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crSResize   - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crSEResize  - Indicate that some edge is to be moved. For example, the 'se-resize' cursor is used when the movement starts from the south-east corner of the box.
        *
        * crAuto      - The UA determines the cursor to display based on the current context.
        *
        * @return enum
        */
        function getCursor() { return $this->_cursor; }
        function setCursor($value) { $this->_cursor=$value; }
        function defaultCursor() { return crNone; }

        /**
         * Parse the cursor CSS property.
         *
         * If defined by a constant, the first two characters are returned; the input string is returned otherwise.
         *
         * For example, if crAuto is passes, "auto" is returned, while if "url(image.csr)" is passed, it is returned
         * the same way ("url(image.csr)").
         *
         * @internal
         */
        function parseCSSCursor()
        {

            if ($this->_cursor != crNone)
            {
                if (($this->_cursor==crAuto) || ($this->_cursor==crCrossHair) || ($this->_cursor==crDefault) || ($this->_cursor==crText) ||
                    ($this->_cursor==crWait) || ($this->_cursor==crHelp) || ($this->_cursor==crPointer) || ($this->_cursor=='crE-Resize') ||
                    ($this->_cursor=='crN-Resize') ||  ($this->_cursor=='crNE-Resize') || ($this->_cursor=='crNW-Resize') ||
                    ($this->_cursor=='crW-Resize') || ($this->_cursor=='crSW-Resize') || ($this->_cursor=='crS-Resize') ||
                    ($this->_cursor=='crSE-Resize')  || ($this->_cursor==crMove) || ($this->_cursor==crProgress) )
                      $cr =  strtolower(substr($this->_cursor, 2));
                else
                      $cr = $this->_cursor;

                return "cursor: $cr;\n";
            }
            else
                return "";


        }
        /**
        * Specifies the text to show in a tooltip when the mouse is over the control for some time.
        *
        * Use the Hint property to provide a string of help text, either as a Help Hint
        * or as help text on a particular location such as a status bar.
        *
        * A Help Hint is a box containing help text that appears for a control when
        * the user moves the mouse pointer over the control and pauses momentarily.
        *
        * To set up Help Hints:
        *
        * Specify the Hint property of each control for which a Help Hint should appear.
        *
        * Set the ShowHint property of each appropriate control to true, or set the
        * ParentShowHint property of all controls to true and set the ShowHint
        * property of the form to true.
        *
        * @see readShowHint(), readParentShowHint()
        * @example ParentProperties/parentproperties.php How parent properties work
        * @return string
        */
        function getHint() { return $this->_hint; }
        function setHint($value) { $this->_hint=$value; }
        function defaultHint() { return ""; }

}

/**
 * Base class for controls with input focus.
 *
 * Inherit from this class if you expect your control to hold another controls as
 * it provides the Layout property, useful when generating the component code that contain
 * other controls.
 *
 */
class FocusControl extends Control
{
        protected $_layout=null;
        public    $controls;

        /**
        * If this control has any children that have ParentFont==true, then
        * this function will assign the same Font property to all children Font properties.
        * Note: This must be in FocusControl, not in Control, as it is here where the Controls property is defined.
        * @see updateChildrenColors(), updateChildrenShowHints(), updateChildrenParentProperties()
        */
        function updateChildrenFonts()
        {
                //Iterates through all child controls and assign the new font
                //to all that have ParentFont=true.
                reset($this->controls->items);
                while (list($k,$v) = each($this->controls->items))
                {
                        if ($v->ParentFont)
                        {
                                $v->updateParentFont();
                        }
                }
        }

        /**
        * Updates the colors for all the children if parentcolor is set.
        * @see updateChildrenFonts(), updateChildrenShowHints(), updateChildrenParentProperties()
        */
        function updateChildrenColors()
        {
                //Iterates through all child controls and assigns the new color
                //to all that have ParentColor=true.
                reset($this->controls->items);
                while (list($k,$v) = each($this->controls->items))
                {
                        if ($v->ParentColor)
                        {
                                $v->updateParentColor();
                        }
                }
        }

        /**
        * Updates the ShowHints properties for all children controls.
        * @see updateChildrenFonts(), updateChildrenColors(), updateChildrenParentProperties()
        */
        function updateChildrenShowHints()
        {
                //Iterates through all child controls and assigns the new showhint
                //to all that have ParentShowHint=true.
                reset($this->controls->items);
                while (list($k,$v) = each($this->controls->items))
                {
                        if ($v->ParentShowHint)
                        {
                                $v->updateParentShowHint();
                        }
                }
        }

        /**
        * Updates all necessary properties for any children that use property values from their parent.
        * Note: This must be in FocusControl, not in Control, as it is here where the Controls property is defined.
        * @see updateChildrenFonts(), updateChildrenColors(), updateChildrenShowHints()
        */
        function updateChildrenParentProperties()
        {
                //Iterates through all child controls and assigns the new font
                //to all that have ParentFont=true.
                reset($this->controls->items);
                while (list($k,$v) = each($this->controls->items))
                {
                        // First checks if it is really necessary to update the parent properties.
                        if ($v->ParentColor || $v->ParentFont || $v->ParentShowHint)
                        {
                                $v->updateParentProperties();
                        }
                }
        }


        function __construct($aowner=null)
        {
                //Creates the controls list.
                $this->controls=new Collection();

                //Calls inherited constructor.
                parent::__construct($aowner);

                $this->_layout=new Layout();
                $this->_layout->_control=$this;
        }

        /**
        * Returns the number of controls for which this control is the Parent.
        * Controls have a Controls property which is a Collection, and this method
        * returns the number of items on that list.
        *
        * @see $controls
        *
        * @return integer
        */
        function readControlCount() { return $this->controls->count(); }

        /**
        * Specifies the Layout this control uses to render its controls to the browser.
        * This property is a Layout object which uses the Controls property of the control
        * to dump components to the browser, depending on the type of Layout.
        *
        * @return Layout
        */
        function readLayout() { return $this->_layout; }
        function writeLayout($value) { if (is_object($value)) $this->_layout=$value; }


        /**
        * Dumps all children iterating through the Controls property and calls the
        * show method of each one.
        * @see $controls
        *
        */
        function dumpChildren()
        {
                //Iterates through controls calling show for all of them.
                reset($this->controls->items);
                while (list($k,$v)=each($this->controls->items))
                {
                        $v->show();
                }

        }

        /**
         * Returns "autofocus" if the component is the active control of its owner, or an empty string otherwise.
         *
         * @internal
         */
        function isActiveControl()
        {
             return ($this === $this->owner->getActiveControl()) ? "autofocus": "";
        }

}

/**
 * Base class for custom control.
 *
 * This class doesn't implement yet any property/method/event, we reserve this
 * stage in the class library to add more features in the future.
 *
 */
class CustomControl extends FocusControl
{
}

/**
 * Base class for controls with graphic capabilities.
 *
 * Right now it doesn't provide any specific properties/methods/events, we reserve
 * this ancestor for future use.
 */
class GraphicControl extends Control
{
}

/**
* CustomListControl is the base class for controls that display a list of items.
*
* It provides an abstract interface to implement for controls with a list of items.
*
*/
abstract class CustomListControl extends FocusControl
{
        protected $_itemindex = -1;

        /**
        * Returns the number of items in the list.
        * @return integer Number of items in the list.
        */
        abstract function readCount();

        /**
        * Returns the value of the ItemIndex property.
        * @return mixed Return the ItemIndex of the list.
        */
        abstract function readItemIndex();

        /**
        * Sets new ItemIndex value.
        * @param mixed $value Value of the new ItemIndex.
        */
        abstract function writeItemIndex($value);

        /**
        * Returns default ItemIndex.
        * @return mixed Returns default ItemIndex.
        */
        abstract function defaultItemIndex();

        /**
        * Adds an item to the list control.
        * @param mixed $item Value of item to add.
        * @param mixed $object ObjectNew1 to assign to the $item. is_object() is used to
        *                      test if $object is an object.
        * @param mixed $itemkey Key of the item in the array. Default key is used if null.
        * @return integer Return the number of items in the list.
        */
        abstract function AddItem($item, $object = null, $itemkey = null);

        /**
        * Deletes all of the items from the list control.
        */
        abstract function Clear();

        /**
        * Removes the selection, leaving all items unselected.
        */
        abstract function ClearSelection();
        //abstract function CopySelection($destination);
        //abstract function DeleteSelection();
        //abstract function MoveSelection($destination);

        /**
        * Selects all items or all text in the selected item.
        */
        abstract function SelectAll();
}

/**
* CustomMultiSelectListControl is the base class for controls that display a list of items and provide multiselection.
*
* It provides an abstract interface to implement for controls with a list of items in which user can select a range of items.
*
*/
abstract class CustomMultiSelectListControl extends CustomListControl
{
        protected $_multiselect = 0;

        /**
         * Amount of selected items in the list.
         */
        abstract function readSelCount();

        /**
         * Whether multiple items can be selected at the same time or not.
         */
        abstract function readMultiSelect();
        abstract function writeMultiSelect($value);
        abstract function defaultMultiSelect();
}

?>
