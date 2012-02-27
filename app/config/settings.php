<?php
/**
 * This is the Ballista configuration file.
 *
 * Use it to configure the behavior of Ballista
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

/**
 * The name of your git master branch.
 * Leave the default if you do not know or you use master as your master branch
 */  
 Configure::write('Ballista.master', 'master');

/**
 * The SSH port to use when logging on to your servers for deploy.
 * The default is 22. 
 */  
 Configure::write('Ballista.sshPort', 22);

/**
 * The username to use when logging on your servers.
 */  
 Configure::write('Ballista.remoteUser', '');

/** 
 * The password for the user above to login to the servers.
 * You must use a password or use the private key option below.
 * This can be left empty if the below given privateKey option is being used.
 */  
 Configure::write('Ballista.remotePass', '');

/** 
 * For authentication without password, to do key verification we require the private key of the remoteUser.
 * Specify the full path or copy the user's ~/.ssh/id_rsa file into this config folder and specify the filename below.
 * Make sure the file has read permissions.
 */  
 Configure::write('Ballista.privateKey', '');
  
/**
 * The normal access protocol of the central repository server where all the Git repositories reside.
 * Use hostname or IP along with whatever transport protocol you use. 
 * Ex: ssh://myrepo.com/ or git://myrepo.com/
 * To clone projects with this protocol, Ballista will use the "repository path", Eg: git clone git@repository.com:path_to_repository 
 * Leave empty if you use gitolite or if your projects are hosted on github.
 */  
 Configure::write('Ballista.accessProtocol', '');

/**
 * If using gitolite, then enable this option
 * Use gitolite username along with server name or IP. 
 * Ex: git@repository.com
 * To clone projects with this protocol, Ballista will use the "project name", Eg: git clone git@repository.com:project_name 
 */  
 //Configure::write('Ballista.gitoliteProtocol', '');
  
/**
 * If using github as your central repository from which you wish to
 * deploy your code to your servers, then enable the following lines.
 * Specify your Github account name here.
 * For example, if your project is at https://github.com/somename/project, then 'somename' would be your account name.
 */  
 //Configure::write('Ballista.githubAccount', '');
 
/**
 * This is the API token to your account if you have a private github account
 * Leave it blank if you don't have a private account.
 */  
 //Configure::write('Ballista.githubToken', '');
 
/**
 * This is the from email address that Ballista will use when sending out
 * email notifications. This field should not be empty!
 */
 Configure::write('Ballista.mail', 'ballista@localhost');
 
/**
 * This is the default notification receivers who will receive the notification message 
 * when a deploy action is performed. Each project can also have its own receivers list 
 * in the projects' page to override this default receivers list.
 */
 Configure::write('Ballista.notifyUsers', '');
 
/**
 * This is the subject text that Ballista will use when sending out
 * email notifications. 
 * The [project] and [comment] enclosures will be substituted with the 
 * project name and the deploy comment (or last commit message) respectively.
 */
 Configure::write('Ballista.notifySubject', 'Ballista: {project} has been deployed - {comment}');

/**
 * This is the default notification message that can be sent to notification
 * receivers when a deploy action is performed. Each project can also have its own 
 * message in the projects' page to override this default message.
 */
 Configure::write('Ballista.notifyMessage', 'A new version has been deployed.');

/**
 * This is the URL to the web interface of git if you
 * have gitweb or anything similar. The URL should be the page to view
 * the details of a particular commit. Ballista will substitute the values
 * of {project} and {commit} ID/hash in your URL.
 */
 Configure::write('Ballista.gitWebUrl', 'http://www.company.com/gitweb/?p={project}.git;a=commit;h={commit}');
