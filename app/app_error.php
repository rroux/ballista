<?php
/**
 * App Error Handler
 *
 * This file handles the error messages in the system
 * and redirects to either the access denied or missing path
 * error message.
 *
 * Ballista : Code Deployment System
 * Copyright 2011-2012, Aller Internett AS <it@allerinternett.no>
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
 * @copyright     Copyright 2011-2012, Aller Internett AS <it@allerinternett.no>
 * @author        Baheerathan Vykundanathan <thamba@allerinternett.no>
 * @package       Ballista
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.txt)
 */

class AppError extends ErrorHandler {

  /**
   * All access denied errors are received and its corresponding
   * error message template is fetched and shown to user
   */
  function accessDenied() {
    $this->_outputMessage('accessDenied');
  }

  /**
   * All 404 errors are received and its corresponding
   * error message template is fetched and shown to user
   */
  function missingPath() {
    $this->_outputMessage('missingPath');
  }

}
?>
