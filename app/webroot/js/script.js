/**
 * Ballista javascript
 *
 * Ballista : Code Deployment System
 * Copyright 2011-2012, Baheerathan Vykundanathan <thamba@allerinternett.no>
 *
 * This file is part of Ballista.
 * 
 * Ballista is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Ballista is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Ballista.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright     Copyright 2011-2012, Baheerathan Vykundanathan <thamba@allerinternett.no>
 * @package       Ballista
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.txt)
 */

$(document).ready(function() {

  // Fade out flash messages
  setTimeout(function () { $('#flashMessage').fadeOut(2000); }, 3000);

  // Highlight all input boxes on focus
  $('input').live('focus', function() { 
	  $(this).addClass('highlight');
  });
  $('input').live('blur', function() { 
	  $(this).removeClass('highlight');
  });
  
});

