<?php
class GitPHP_Mime
{
    protected $filename;
	protected $ext;
	/**
	 * File extensions that are viewable in browser
	 */
	protected $image_exts = array('ico', 'gif', 'jpg', 'jpeg', 'png', 'bmp');
	protected $video_exts = array('webm', 'mp4', 'm4v', 'ogm', 'ogv', 'mov');
	protected $audio_exts = array('wav', 'mp3', 'ogg', 'm4a');
    
    public function __construct($filename)
    {
        $this->filename = basename($filename);
		$this->ext = $this->fileExtension($this->filename);
    }
    /**
     * Returns mime type for a given extension or if no extension is provided,
     * all mime types in an associative array, with extensions as keys. 
     * If extension is unknown, returns null.
     * (extracted from Orbit source http"//orbit.luaforge.net/).
     *
     *
     * @param string $extzzzzz
     * @return string, array, null
     */
    public function fileMimeType($ext = null)
    {
		static $extension_mime = array(
		   '123' => 'application/vnd.lotus-1-2-3',
		   '3dml' => 'text/vnd.in3d.3dml',
		   '3ds' => 'image/x-3ds',
		   '3g2' => 'video/3gpp2',
		   '3gp' => 'video/3gpp',
		   '7z' => 'application/x-7z-compressed',
		   'aab' => 'application/x-authorware-bin',
		   'aac' => 'audio/x-aac',
		   'aam' => 'application/x-authorware-map',
		   'aas' => 'application/x-authorware-seg',
		   'abw' => 'application/x-abiword',
		   'ac' => 'application/pkix-attr-cert',
		   'acc' => 'application/vnd.americandynamics.acc',
		   'ace' => 'application/x-ace-compressed',
		   'acu' => 'application/vnd.acucobol',
		   'acutc' => 'application/vnd.acucorp',
		   'adp' => 'audio/adpcm',
		   'aep' => 'application/vnd.audiograph',
		   'afm' => 'application/x-font-type1',
		   'afp' => 'application/vnd.ibm.modcap',
		   'ahead' => 'application/vnd.ahead.space',
		   'ai' => 'application/postscript',
		   'aif' => 'audio/x-aiff',
		   'aifc' => 'audio/x-aiff',
		   'aiff' => 'audio/x-aiff',
		   'air' => 'application/vnd.adobe.air-application-installer-package+zip',
		   'ait' => 'application/vnd.dvb.ait',
		   'ami' => 'application/vnd.amiga.ami',
		   'apk' => 'application/vnd.android.package-archive',
		   'appcache' => 'text/cache-manifest',
		   'application' => 'application/x-ms-application',
		   'apr' => 'application/vnd.lotus-approach',
		   'arc' => 'application/x-freearc',
		   'asc' => 'application/pgp-signature',
		   'asf' => 'video/x-ms-asf',
		   'asm' => 'text/x-asm',
		   'aso' => 'application/vnd.accpac.simply.aso',
		   'asx' => 'video/x-ms-asf',
		   'atc' => 'application/vnd.acucorp',
		   'atom' => 'application/atom+xml',
		   'atomcat' => 'application/atomcat+xml',
		   'atomsvc' => 'application/atomsvc+xml',
		   'atx' => 'application/vnd.antix.game-component',
		   'au' => 'audio/basic',
		   'avi' => 'video/x-msvideo',
		   'aw' => 'application/applixware',
		   'azf' => 'application/vnd.airzip.filesecure.azf',
		   'azs' => 'application/vnd.airzip.filesecure.azs',
		   'azw' => 'application/vnd.amazon.ebook',
		   'bat' => 'application/x-msdownload',
		   'bcpio' => 'application/x-bcpio',
		   'bdf' => 'application/x-font-bdf',
		   'bdm' => 'application/vnd.syncml.dm+wbxml',
		   'bed' => 'application/vnd.realvnc.bed',
		   'bh2' => 'application/vnd.fujitsu.oasysprs',
		   'bin' => 'application/octet-stream',
		   'blb' => 'application/x-blorb',
		   'blorb' => 'application/x-blorb',
		   'bmi' => 'application/vnd.bmi',
		   'bmp' => 'image/bmp',
		   'book' => 'application/vnd.framemaker',
		   'box' => 'application/vnd.previewsystems.box',
		   'boz' => 'application/x-bzip2',
		   'bpk' => 'application/octet-stream',
		   'btif' => 'image/prs.btif',
		   'bz' => 'application/x-bzip',
		   'bz2' => 'application/x-bzip2',
		   'c' => 'text/x-c',
		   'c11amc' => 'application/vnd.cluetrust.cartomobile-config',
		   'c11amz' => 'application/vnd.cluetrust.cartomobile-config-pkg',
		   'c4d' => 'application/vnd.clonk.c4group',
		   'c4f' => 'application/vnd.clonk.c4group',
		   'c4g' => 'application/vnd.clonk.c4group',
		   'c4p' => 'application/vnd.clonk.c4group',
		   'c4u' => 'application/vnd.clonk.c4group',
		   'cab' => 'application/vnd.ms-cab-compressed',
		   'caf' => 'audio/x-caf',
		   'cap' => 'application/vnd.tcpdump.pcap',
		   'car' => 'application/vnd.curl.car',
		   'cat' => 'application/vnd.ms-pki.seccat',
		   'cb7' => 'application/x-cbr',
		   'cba' => 'application/x-cbr',
		   'cbr' => 'application/x-cbr',
		   'cbt' => 'application/x-cbr',
		   'cbz' => 'application/x-cbr',
		   'cc' => 'text/x-c',
		   'cct' => 'application/x-director',
		   'ccxml' => 'application/ccxml+xml',
		   'cdbcmsg' => 'application/vnd.contact.cmsg',
		   'cdf' => 'application/x-netcdf',
		   'cdkey' => 'application/vnd.mediastation.cdkey',
		   'cdmia' => 'application/cdmi-capability',
		   'cdmic' => 'application/cdmi-container',
		   'cdmid' => 'application/cdmi-domain',
		   'cdmio' => 'application/cdmi-object',
		   'cdmiq' => 'application/cdmi-queue',
		   'cdx' => 'chemical/x-cdx',
		   'cdxml' => 'application/vnd.chemdraw+xml',
		   'cdy' => 'application/vnd.cinderella',
		   'cer' => 'application/pkix-cert',
		   'cfs' => 'application/x-cfs-compressed',
		   'cgm' => 'image/cgm',
		   'chat' => 'application/x-chat',
		   'chm' => 'application/vnd.ms-htmlhelp',
		   'chrt' => 'application/vnd.kde.kchart',
		   'cif' => 'chemical/x-cif',
		   'cii' => 'application/vnd.anser-web-certificate-issue-initiation',
		   'cil' => 'application/vnd.ms-artgalry',
		   'cla' => 'application/vnd.claymore',
		   'class' => 'application/java-vm',
		   'clkk' => 'application/vnd.crick.clicker.keyboard',
		   'clkp' => 'application/vnd.crick.clicker.palette',
		   'clkt' => 'application/vnd.crick.clicker.template',
		   'clkw' => 'application/vnd.crick.clicker.wordbank',
		   'clkx' => 'application/vnd.crick.clicker',
		   'clp' => 'application/x-msclip',
		   'cmc' => 'application/vnd.cosmocaller',
		   'cmdf' => 'chemical/x-cmdf',
		   'cml' => 'chemical/x-cml',
		   'cmp' => 'application/vnd.yellowriver-custom-menu',
		   'cmx' => 'image/x-cmx',
		   'cod' => 'application/vnd.rim.cod',
		   'com' => 'application/x-msdownload',
		   'conf' => 'text/plain',
		   'cpio' => 'application/x-cpio',
		   'cpp' => 'text/x-c',
		   'cpt' => 'application/mac-compactpro',
		   'crd' => 'application/x-mscardfile',
		   'crl' => 'application/pkix-crl',
		   'crt' => 'application/x-x509-ca-cert',
		   'cryptonote' => 'application/vnd.rig.cryptonote',
		   'csh' => 'application/x-csh',
		   'csml' => 'chemical/x-csml',
		   'csp' => 'application/vnd.commonspace',
		   'css' => 'text/css',
		   'cst' => 'application/x-director',
		   'csv' => 'text/csv',
		   'cu' => 'application/cu-seeme',
		   'curl' => 'text/vnd.curl',
		   'cww' => 'application/prs.cww',
		   'cxt' => 'application/x-director',
		   'cxx' => 'text/x-c',
		   'dae' => 'model/vnd.collada+xml',
		   'daf' => 'application/vnd.mobius.daf',
		   'dart' => 'application/vnd.dart',
		   'dataless' => 'application/vnd.fdsn.seed',
		   'davmount' => 'application/davmount+xml',
		   'dbk' => 'application/docbook+xml',
		   'dcr' => 'application/x-director',
		   'dcurl' => 'text/vnd.curl.dcurl',
		   'dd2' => 'application/vnd.oma.dd2+xml',
		   'ddd' => 'application/vnd.fujixerox.ddd',
		   'deb' => 'application/x-debian-package',
		   'def' => 'text/plain',
		   'deploy' => 'application/octet-stream',
		   'der' => 'application/x-x509-ca-cert',
		   'dfac' => 'application/vnd.dreamfactory',
		   'dgc' => 'application/x-dgc-compressed',
		   'dic' => 'text/x-c',
		   'dir' => 'application/x-director',
		   'dis' => 'application/vnd.mobius.dis',
		   'dist' => 'application/octet-stream',
		   'distz' => 'application/octet-stream',
		   'djv' => 'image/vnd.djvu',
		   'djvu' => 'image/vnd.djvu',
		   'dll' => 'application/x-msdownload',
		   'dmg' => 'application/x-apple-diskimage',
		   'dmp' => 'application/vnd.tcpdump.pcap',
		   'dms' => 'application/octet-stream',
		   'dna' => 'application/vnd.dna',
		   'doc' => 'application/msword',
		   'docm' => 'application/vnd.ms-word.document.macroenabled.12',
		   'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		   'dot' => 'application/msword',
		   'dotm' => 'application/vnd.ms-word.template.macroenabled.12',
		   'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		   'dp' => 'application/vnd.osgi.dp',
		   'dpg' => 'application/vnd.dpgraph',
		   'dra' => 'audio/vnd.dra',
		   'dsc' => 'text/prs.lines.tag',
		   'dssc' => 'application/dssc+der',
		   'dtb' => 'application/x-dtbook+xml',
		   'dtd' => 'application/xml-dtd',
		   'dts' => 'audio/vnd.dts',
		   'dtshd' => 'audio/vnd.dts.hd',
		   'dump' => 'application/octet-stream',
		   'dvb' => 'video/vnd.dvb.file',
		   'dvi' => 'application/x-dvi',
		   'dwf' => 'model/vnd.dwf',
		   'dwg' => 'image/vnd.dwg',
		   'dxf' => 'image/vnd.dxf',
		   'dxp' => 'application/vnd.spotfire.dxp',
		   'dxr' => 'application/x-director',
		   'ecelp4800' => 'audio/vnd.nuera.ecelp4800',
		   'ecelp7470' => 'audio/vnd.nuera.ecelp7470',
		   'ecelp9600' => 'audio/vnd.nuera.ecelp9600',
		   'ecma' => 'application/ecmascript',
		   'edm' => 'application/vnd.novadigm.edm',
		   'edx' => 'application/vnd.novadigm.edx',
		   'efif' => 'application/vnd.picsel',
		   'ei6' => 'application/vnd.pg.osasli',
		   'elc' => 'application/octet-stream',
		   'emf' => 'application/x-msmetafile',
		   'eml' => 'message/rfc822',
		   'emma' => 'application/emma+xml',
		   'emz' => 'application/x-msmetafile',
		   'eol' => 'audio/vnd.digital-winds',
		   'eot' => 'application/vnd.ms-fontobject',
		   'eps' => 'application/postscript',
		   'epub' => 'application/epub+zip',
		   'es3' => 'application/vnd.eszigno3+xml',
		   'esa' => 'application/vnd.osgi.subsystem',
		   'esf' => 'application/vnd.epson.esf',
		   'et3' => 'application/vnd.eszigno3+xml',
		   'etx' => 'text/x-setext',
		   'eva' => 'application/x-eva',
		   'evy' => 'application/x-envoy',
		   'exe' => 'application/x-msdownload',
		   'exi' => 'application/exi',
		   'ext' => 'application/vnd.novadigm.ext',
		   'ez' => 'application/andrew-inset',
		   'ez2' => 'application/vnd.ezpix-album',
		   'ez3' => 'application/vnd.ezpix-package',
		   'f' => 'text/x-fortran',
		   'f4v' => 'video/x-f4v',
		   'f77' => 'text/x-fortran',
		   'f90' => 'text/x-fortran',
		   'fbs' => 'image/vnd.fastbidsheet',
		   'fcdt' => 'application/vnd.adobe.formscentral.fcdt',
		   'fcs' => 'application/vnd.isac.fcs',
		   'fdf' => 'application/vnd.fdf',
		   'fe_launch' => 'application/vnd.denovo.fcselayout-link',
		   'fg5' => 'application/vnd.fujitsu.oasysgp',
		   'fgd' => 'application/x-director',
		   'fh' => 'image/x-freehand',
		   'fh4' => 'image/x-freehand',
		   'fh5' => 'image/x-freehand',
		   'fh7' => 'image/x-freehand',
		   'fhc' => 'image/x-freehand',
		   'fig' => 'application/x-xfig',
		   'flac' => 'audio/x-flac',
		   'fli' => 'video/x-fli',
		   'flo' => 'application/vnd.micrografx.flo',
		   'flv' => 'video/x-flv',
		   'flw' => 'application/vnd.kde.kivio',
		   'flx' => 'text/vnd.fmi.flexstor',
		   'fly' => 'text/vnd.fly',
		   'fm' => 'application/vnd.framemaker',
		   'fnc' => 'application/vnd.frogans.fnc',
		   'for' => 'text/x-fortran',
		   'fpx' => 'image/vnd.fpx',
		   'frame' => 'application/vnd.framemaker',
		   'fsc' => 'application/vnd.fsc.weblaunch',
		   'fst' => 'image/vnd.fst',
		   'ftc' => 'application/vnd.fluxtime.clip',
		   'fti' => 'application/vnd.anser-web-funds-transfer-initiation',
		   'fvt' => 'video/vnd.fvt',
		   'fxp' => 'application/vnd.adobe.fxp',
		   'fxpl' => 'application/vnd.adobe.fxp',
		   'fzs' => 'application/vnd.fuzzysheet',
		   'g2w' => 'application/vnd.geoplan',
		   'g3' => 'image/g3fax',
		   'g3w' => 'application/vnd.geospace',
		   'gac' => 'application/vnd.groove-account',
		   'gam' => 'application/x-tads',
		   'gbr' => 'application/rpki-ghostbusters',
		   'gca' => 'application/x-gca-compressed',
		   'gdl' => 'model/vnd.gdl',
		   'geo' => 'application/vnd.dynageo',
		   'gex' => 'application/vnd.geometry-explorer',
		   'ggb' => 'application/vnd.geogebra.file',
		   'ggt' => 'application/vnd.geogebra.tool',
		   'ghf' => 'application/vnd.groove-help',
		   'gif' => 'image/gif',
		   'gim' => 'application/vnd.groove-identity-message',
		   'gml' => 'application/gml+xml',
		   'gmx' => 'application/vnd.gmx',
		   'gnumeric' => 'application/x-gnumeric',
		   'gph' => 'application/vnd.flographit',
		   'gpx' => 'application/gpx+xml',
		   'gqf' => 'application/vnd.grafeq',
		   'gqs' => 'application/vnd.grafeq',
		   'gram' => 'application/srgs',
		   'gramps' => 'application/x-gramps-xml',
		   'gre' => 'application/vnd.geometry-explorer',
		   'grv' => 'application/vnd.groove-injector',
		   'grxml' => 'application/srgs+xml',
		   'gsf' => 'application/x-font-ghostscript',
		   'gtar' => 'application/x-gtar',
		   'gtm' => 'application/vnd.groove-tool-message',
		   'gtw' => 'model/vnd.gtw',
		   'gv' => 'text/vnd.graphviz',
		   'gxf' => 'application/gxf',
		   'gxt' => 'application/vnd.geonext',
		   'h' => 'text/x-c',
		   'h261' => 'video/h261',
		   'h263' => 'video/h263',
		   'h264' => 'video/h264',
		   'hal' => 'application/vnd.hal+xml',
		   'hbci' => 'application/vnd.hbci',
		   'hdf' => 'application/x-hdf',
		   'hh' => 'text/x-c',
		   'hlp' => 'application/winhlp',
		   'hpgl' => 'application/vnd.hp-hpgl',
		   'hpid' => 'application/vnd.hp-hpid',
		   'hps' => 'application/vnd.hp-hps',
		   'hqx' => 'application/mac-binhex40',
		   'htke' => 'application/vnd.kenameaapp',
		   'htm' => 'text/html',
		   'html' => 'text/html',
		   'hvd' => 'application/vnd.yamaha.hv-dic',
		   'hvp' => 'application/vnd.yamaha.hv-voice',
		   'hvs' => 'application/vnd.yamaha.hv-script',
		   'i2g' => 'application/vnd.intergeo',
		   'icc' => 'application/vnd.iccprofile',
		   'ice' => 'x-conference/x-cooltalk',
		   'icm' => 'application/vnd.iccprofile',
		   'ico' => 'image/x-icon',
		   'ics' => 'text/calendar',
		   'ief' => 'image/ief',
		   'ifb' => 'text/calendar',
		   'ifm' => 'application/vnd.shana.informed.formdata',
		   'iges' => 'model/iges',
		   'igl' => 'application/vnd.igloader',
		   'igm' => 'application/vnd.insors.igm',
		   'igs' => 'model/iges',
		   'igx' => 'application/vnd.micrografx.igx',
		   'iif' => 'application/vnd.shana.informed.interchange',
		   'imp' => 'application/vnd.accpac.simply.imp',
		   'ims' => 'application/vnd.ms-ims',
		   'in' => 'text/plain',
		   'ink' => 'application/inkml+xml',
		   'inkml' => 'application/inkml+xml',
		   'install' => 'application/x-install-instructions',
		   'iota' => 'application/vnd.astraea-software.iota',
		   'ipfix' => 'application/ipfix',
		   'ipk' => 'application/vnd.shana.informed.package',
		   'irm' => 'application/vnd.ibm.rights-management',
		   'irp' => 'application/vnd.irepository.package+xml',
		   'iso' => 'application/x-iso9660-image',
		   'itp' => 'application/vnd.shana.informed.formtemplate',
		   'ivp' => 'application/vnd.immervision-ivp',
		   'ivu' => 'application/vnd.immervision-ivu',
		   'jad' => 'text/vnd.sun.j2me.app-descriptor',
		   'jam' => 'application/vnd.jam',
		   'jar' => 'application/java-archive',
		   'java' => 'text/x-java-source',
		   'jisp' => 'application/vnd.jisp',
		   'jlt' => 'application/vnd.hp-jlyt',
		   'jnlp' => 'application/x-java-jnlp-file',
		   'joda' => 'application/vnd.joost.joda-archive',
		   'jpe' => 'image/jpeg',
		   'jpeg' => 'image/jpeg',
		   'jpg' => 'image/jpeg',
		   'jpgm' => 'video/jpm',
		   'jpgv' => 'video/jpeg',
		   'jpm' => 'video/jpm',
		   'js' => 'application/javascript',
		   'json' => 'application/json',
		   'jsonml' => 'application/jsonml+json',
		   'kar' => 'audio/midi',
		   'karbon' => 'application/vnd.kde.karbon',
		   'kfo' => 'application/vnd.kde.kformula',
		   'kia' => 'application/vnd.kidspiration',
		   'kml' => 'application/vnd.google-earth.kml+xml',
		   'kmz' => 'application/vnd.google-earth.kmz',
		   'kne' => 'application/vnd.kinar',
		   'knp' => 'application/vnd.kinar',
		   'kon' => 'application/vnd.kde.kontour',
		   'kpr' => 'application/vnd.kde.kpresenter',
		   'kpt' => 'application/vnd.kde.kpresenter',
		   'kpxx' => 'application/vnd.ds-keypoint',
		   'ksp' => 'application/vnd.kde.kspread',
		   'ktr' => 'application/vnd.kahootz',
		   'ktx' => 'image/ktx',
		   'ktz' => 'application/vnd.kahootz',
		   'kwd' => 'application/vnd.kde.kword',
		   'kwt' => 'application/vnd.kde.kword',
		   'lasxml' => 'application/vnd.las.las+xml',
		   'latex' => 'application/x-latex',
		   'lbd' => 'application/vnd.llamagraphics.life-balance.desktop',
		   'lbe' => 'application/vnd.llamagraphics.life-balance.exchange+xml',
		   'les' => 'application/vnd.hhe.lesson-player',
		   'lha' => 'application/x-lzh-compressed',
		   'link66' => 'application/vnd.route66.link66+xml',
		   'list' => 'text/plain',
		   'list3820' => 'application/vnd.ibm.modcap',
		   'listafp' => 'application/vnd.ibm.modcap',
		   'lnk' => 'application/x-ms-shortcut',
		   'log' => 'text/plain',
		   'lostxml' => 'application/lost+xml',
		   'lrf' => 'application/octet-stream',
		   'lrm' => 'application/vnd.ms-lrm',
		   'ltf' => 'application/vnd.frogans.ltf',
		   'lvp' => 'audio/vnd.lucent.voice',
		   'lwp' => 'application/vnd.lotus-wordpro',
		   'lzh' => 'application/x-lzh-compressed',
		   'm13' => 'application/x-msmediaview',
		   'm14' => 'application/x-msmediaview',
		   'm1v' => 'video/mpeg',
		   'm21' => 'application/mp21',
		   'm2a' => 'audio/mpeg',
		   'm2v' => 'video/mpeg',
		   'm3a' => 'audio/mpeg',
		   'm3u' => 'audio/x-mpegurl',
		   'm3u8' => 'application/vnd.apple.mpegurl',
		   'm4a' => 'audio/mp4',
		   'm4u' => 'video/vnd.mpegurl',
		   'm4v' => 'video/x-m4v',
		   'ma' => 'application/mathematica',
		   'mads' => 'application/mads+xml',
		   'mag' => 'application/vnd.ecowin.chart',
		   'maker' => 'application/vnd.framemaker',
		   'man' => 'text/troff',
		   'mar' => 'application/octet-stream',
		   'mathml' => 'application/mathml+xml',
		   'mb' => 'application/mathematica',
		   'mbk' => 'application/vnd.mobius.mbk',
		   'mbox' => 'application/mbox',
		   'mc1' => 'application/vnd.medcalcdata',
		   'mcd' => 'application/vnd.mcd',
		   'mcurl' => 'text/vnd.curl.mcurl',
		   'mdb' => 'application/x-msaccess',
		   'mdi' => 'image/vnd.ms-modi',
		   'me' => 'text/troff',
		   'mesh' => 'model/mesh',
		   'meta4' => 'application/metalink4+xml',
		   'metalink' => 'application/metalink+xml',
		   'mets' => 'application/mets+xml',
		   'mfm' => 'application/vnd.mfmp',
		   'mft' => 'application/rpki-manifest',
		   'mgp' => 'application/vnd.osgeo.mapguide.package',
		   'mgz' => 'application/vnd.proteus.magazine',
		   'mid' => 'audio/midi',
		   'midi' => 'audio/midi',
		   'mie' => 'application/x-mie',
		   'mif' => 'application/vnd.mif',
		   'mime' => 'message/rfc822',
		   'mj2' => 'video/mj2',
		   'mjp2' => 'video/mj2',
		   'mk3d' => 'video/x-matroska',
		   'mka' => 'audio/x-matroska',
		   'mks' => 'video/x-matroska',
		   'mkv' => 'video/x-matroska',
		   'mlp' => 'application/vnd.dolby.mlp',
		   'mmd' => 'application/vnd.chipnuts.karaoke-mmd',
		   'mmf' => 'application/vnd.smaf',
		   'mmr' => 'image/vnd.fujixerox.edmics-mmr',
		   'mng' => 'video/x-mng',
		   'mny' => 'application/x-msmoney',
		   'mobi' => 'application/x-mobipocket-ebook',
		   'mods' => 'application/mods+xml',
		   'mov' => 'video/quicktime',
		   'movie' => 'video/x-sgi-movie',
		   'mp2' => 'audio/mpeg',
		   'mp21' => 'application/mp21',
		   'mp2a' => 'audio/mpeg',
		   'mp3' => 'audio/mpeg',
		   'mp4' => 'video/mp4',
		   'mp4a' => 'audio/mp4',
		   'mp4s' => 'application/mp4',
		   'mp4v' => 'video/mp4',
		   'mpc' => 'application/vnd.mophun.certificate',
		   'mpe' => 'video/mpeg',
		   'mpeg' => 'video/mpeg',
		   'mpg' => 'video/mpeg',
		   'mpg4' => 'video/mp4',
		   'mpga' => 'audio/mpeg',
		   'mpkg' => 'application/vnd.apple.installer+xml',
		   'mpm' => 'application/vnd.blueice.multipass',
		   'mpn' => 'application/vnd.mophun.application',
		   'mpp' => 'application/vnd.ms-project',
		   'mpt' => 'application/vnd.ms-project',
		   'mpy' => 'application/vnd.ibm.minipay',
		   'mqy' => 'application/vnd.mobius.mqy',
		   'mrc' => 'application/marc',
		   'mrcx' => 'application/marcxml+xml',
		   'ms' => 'text/troff',
		   'mscml' => 'application/mediaservercontrol+xml',
		   'mseed' => 'application/vnd.fdsn.mseed',
		   'mseq' => 'application/vnd.mseq',
		   'msf' => 'application/vnd.epson.msf',
		   'msh' => 'model/mesh',
		   'msi' => 'application/x-msdownload',
		   'msl' => 'application/vnd.mobius.msl',
		   'msty' => 'application/vnd.muvee.style',
		   'mts' => 'model/vnd.mts',
		   'mus' => 'application/vnd.musician',
		   'musicxml' => 'application/vnd.recordare.musicxml+xml',
		   'mvb' => 'application/x-msmediaview',
		   'mwf' => 'application/vnd.mfer',
		   'mxf' => 'application/mxf',
		   'mxl' => 'application/vnd.recordare.musicxml',
		   'mxml' => 'application/xv+xml',
		   'mxs' => 'application/vnd.triscape.mxs',
		   'mxu' => 'video/vnd.mpegurl',
		   'n-gage' => 'application/vnd.nokia.n-gage.symbian.install',
		   'n3' => 'text/n3',
		   'nb' => 'application/mathematica',
		   'nbp' => 'application/vnd.wolfram.player',
		   'nc' => 'application/x-netcdf',
		   'ncx' => 'application/x-dtbncx+xml',
		   'nfo' => 'text/x-nfo',
		   'ngdat' => 'application/vnd.nokia.n-gage.data',
		   'nitf' => 'application/vnd.nitf',
		   'nlu' => 'application/vnd.neurolanguage.nlu',
		   'nml' => 'application/vnd.enliven',
		   'nnd' => 'application/vnd.noblenet-directory',
		   'nns' => 'application/vnd.noblenet-sealer',
		   'nnw' => 'application/vnd.noblenet-web',
		   'npx' => 'image/vnd.net-fpx',
		   'nsc' => 'application/x-conference',
		   'nsf' => 'application/vnd.lotus-notes',
		   'ntf' => 'application/vnd.nitf',
		   'nzb' => 'application/x-nzb',
		   'oa2' => 'application/vnd.fujitsu.oasys2',
		   'oa3' => 'application/vnd.fujitsu.oasys3',
		   'oas' => 'application/vnd.fujitsu.oasys',
		   'obd' => 'application/x-msbinder',
		   'obj' => 'application/x-tgif',
		   'oda' => 'application/oda',
		   'odb' => 'application/vnd.oasis.opendocument.database',
		   'odc' => 'application/vnd.oasis.opendocument.chart',
		   'odf' => 'application/vnd.oasis.opendocument.formula',
		   'odft' => 'application/vnd.oasis.opendocument.formula-template',
		   'odg' => 'application/vnd.oasis.opendocument.graphics',
		   'odi' => 'application/vnd.oasis.opendocument.image',
		   'odm' => 'application/vnd.oasis.opendocument.text-master',
		   'odp' => 'application/vnd.oasis.opendocument.presentation',
		   'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		   'odt' => 'application/vnd.oasis.opendocument.text',
		   'oga' => 'audio/ogg',
		   'ogg' => 'audio/ogg',
		   'ogv' => 'video/ogg',
		   'ogx' => 'application/ogg',
		   'omdoc' => 'application/omdoc+xml',
		   'onepkg' => 'application/onenote',
		   'onetmp' => 'application/onenote',
		   'onetoc' => 'application/onenote',
		   'onetoc2' => 'application/onenote',
		   'opf' => 'application/oebps-package+xml',
		   'opml' => 'text/x-opml',
		   'oprc' => 'application/vnd.palm',
		   'org' => 'application/vnd.lotus-organizer',
		   'osf' => 'application/vnd.yamaha.openscoreformat',
		   'osfpvg' => 'application/vnd.yamaha.openscoreformat.osfpvg+xml',
		   'otc' => 'application/vnd.oasis.opendocument.chart-template',
		   'otf' => 'font/otf',
		   'otg' => 'application/vnd.oasis.opendocument.graphics-template',
		   'oth' => 'application/vnd.oasis.opendocument.text-web',
		   'oti' => 'application/vnd.oasis.opendocument.image-template',
		   'otp' => 'application/vnd.oasis.opendocument.presentation-template',
		   'ots' => 'application/vnd.oasis.opendocument.spreadsheet-template',
		   'ott' => 'application/vnd.oasis.opendocument.text-template',
		   'oxps' => 'application/oxps',
		   'oxt' => 'application/vnd.openofficeorg.extension',
		   'p' => 'text/x-pascal',
		   'p10' => 'application/pkcs10',
		   'p12' => 'application/x-pkcs12',
		   'p7b' => 'application/x-pkcs7-certificates',
		   'p7c' => 'application/pkcs7-mime',
		   'p7m' => 'application/pkcs7-mime',
		   'p7r' => 'application/x-pkcs7-certreqresp',
		   'p7s' => 'application/pkcs7-signature',
		   'p8' => 'application/pkcs8',
		   'pas' => 'text/x-pascal',
		   'paw' => 'application/vnd.pawaafile',
		   'pbd' => 'application/vnd.powerbuilder6',
		   'pbm' => 'image/x-portable-bitmap',
		   'pcap' => 'application/vnd.tcpdump.pcap',
		   'pcf' => 'application/x-font-pcf',
		   'pcl' => 'application/vnd.hp-pcl',
		   'pclxl' => 'application/vnd.hp-pclxl',
		   'pct' => 'image/x-pict',
		   'pcurl' => 'application/vnd.curl.pcurl',
		   'pcx' => 'image/x-pcx',
		   'pdb' => 'application/vnd.palm',
		   'pdf' => 'application/pdf',
		   'pfa' => 'application/x-font-type1',
		   'pfb' => 'application/x-font-type1',
		   'pfm' => 'application/x-font-type1',
		   'pfr' => 'application/font-tdpfr',
		   'pfx' => 'application/x-pkcs12',
		   'pgm' => 'image/x-portable-graymap',
		   'pgn' => 'application/x-chess-pgn',
		   'pgp' => 'application/pgp-encrypted',
		   'pic' => 'image/x-pict',
		   'pkg' => 'application/octet-stream',
		   'pki' => 'application/pkixcmp',
		   'pkipath' => 'application/pkix-pkipath',
		   'plb' => 'application/vnd.3gpp.pic-bw-large',
		   'plc' => 'application/vnd.mobius.plc',
		   'plf' => 'application/vnd.pocketlearn',
		   'pls' => 'application/pls+xml',
		   'pml' => 'application/vnd.ctc-posml',
		   'png' => 'image/png',
		   'pnm' => 'image/x-portable-anymap',
		   'portpkg' => 'application/vnd.macports.portpkg',
		   'pot' => 'application/vnd.ms-powerpoint',
		   'potm' => 'application/vnd.ms-powerpoint.template.macroenabled.12',
		   'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		   'ppam' => 'application/vnd.ms-powerpoint.addin.macroenabled.12',
		   'ppd' => 'application/vnd.cups-ppd',
		   'ppm' => 'image/x-portable-pixmap',
		   'pps' => 'application/vnd.ms-powerpoint',
		   'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroenabled.12',
		   'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		   'ppt' => 'application/vnd.ms-powerpoint',
		   'pptm' => 'application/vnd.ms-powerpoint.presentation.macroenabled.12',
		   'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		   'pqa' => 'application/vnd.palm',
		   'prc' => 'application/x-mobipocket-ebook',
		   'pre' => 'application/vnd.lotus-freelance',
		   'prf' => 'application/pics-rules',
		   'ps' => 'application/postscript',
		   'psb' => 'application/vnd.3gpp.pic-bw-small',
		   'psd' => 'image/vnd.adobe.photoshop',
		   'psf' => 'application/x-font-linux-psf',
		   'pskcxml' => 'application/pskc+xml',
		   'ptid' => 'application/vnd.pvi.ptid1',
		   'pub' => 'application/x-mspublisher',
		   'pvb' => 'application/vnd.3gpp.pic-bw-var',
		   'pwn' => 'application/vnd.3m.post-it-notes',
		   'pya' => 'audio/vnd.ms-playready.media.pya',
		   'pyv' => 'video/vnd.ms-playready.media.pyv',
		   'qam' => 'application/vnd.epson.quickanime',
		   'qbo' => 'application/vnd.intu.qbo',
		   'qfx' => 'application/vnd.intu.qfx',
		   'qps' => 'application/vnd.publishare-delta-tree',
		   'qt' => 'video/quicktime',
		   'qwd' => 'application/vnd.quark.quarkxpress',
		   'qwt' => 'application/vnd.quark.quarkxpress',
		   'qxb' => 'application/vnd.quark.quarkxpress',
		   'qxd' => 'application/vnd.quark.quarkxpress',
		   'qxl' => 'application/vnd.quark.quarkxpress',
		   'qxt' => 'application/vnd.quark.quarkxpress',
		   'ra' => 'audio/x-pn-realaudio',
		   'ram' => 'audio/x-pn-realaudio',
		   'rar' => 'application/x-rar-compressed',
		   'ras' => 'image/x-cmu-raster',
		   'rcprofile' => 'application/vnd.ipunplugged.rcprofile',
		   'rdf' => 'application/rdf+xml',
		   'rdz' => 'application/vnd.data-vision.rdz',
		   'rep' => 'application/vnd.businessobjects',
		   'res' => 'application/x-dtbresource+xml',
		   'rgb' => 'image/x-rgb',
		   'rif' => 'application/reginfo+xml',
		   'rip' => 'audio/vnd.rip',
		   'ris' => 'application/x-research-info-systems',
		   'rl' => 'application/resource-lists+xml',
		   'rlc' => 'image/vnd.fujixerox.edmics-rlc',
		   'rld' => 'application/resource-lists-diff+xml',
		   'rm' => 'application/vnd.rn-realmedia',
		   'rmi' => 'audio/midi',
		   'rmp' => 'audio/x-pn-realaudio-plugin',
		   'rms' => 'application/vnd.jcp.javame.midlet-rms',
		   'rmvb' => 'application/vnd.rn-realmedia-vbr',
		   'rnc' => 'application/relax-ng-compact-syntax',
		   'roa' => 'application/rpki-roa',
		   'roff' => 'text/troff',
		   'rp9' => 'application/vnd.cloanto.rp9',
		   'rpss' => 'application/vnd.nokia.radio-presets',
		   'rpst' => 'application/vnd.nokia.radio-preset',
		   'rq' => 'application/sparql-query',
		   'rs' => 'application/rls-services+xml',
		   'rsd' => 'application/rsd+xml',
		   'rss' => 'application/rss+xml',
		   'rtf' => 'application/rtf',
		   'rtx' => 'text/richtext',
		   's' => 'text/x-asm',
		   's3m' => 'audio/s3m',
		   'saf' => 'application/vnd.yamaha.smaf-audio',
		   'sbml' => 'application/sbml+xml',
		   'sc' => 'application/vnd.ibm.secure-container',
		   'scd' => 'application/x-msschedule',
		   'scm' => 'application/vnd.lotus-screencam',
		   'scq' => 'application/scvp-cv-request',
		   'scs' => 'application/scvp-cv-response',
		   'scurl' => 'text/vnd.curl.scurl',
		   'sda' => 'application/vnd.stardivision.draw',
		   'sdc' => 'application/vnd.stardivision.calc',
		   'sdd' => 'application/vnd.stardivision.impress',
		   'sdkd' => 'application/vnd.solent.sdkm+xml',
		   'sdkm' => 'application/vnd.solent.sdkm+xml',
		   'sdp' => 'application/sdp',
		   'sdw' => 'application/vnd.stardivision.writer',
		   'see' => 'application/vnd.seemail',
		   'seed' => 'application/vnd.fdsn.seed',
		   'sema' => 'application/vnd.sema',
		   'semd' => 'application/vnd.semd',
		   'semf' => 'application/vnd.semf',
		   'ser' => 'application/java-serialized-object',
		   'setpay' => 'application/set-payment-initiation',
		   'setreg' => 'application/set-registration-initiation',
		   'sfd-hdstx' => 'application/vnd.hydrostatix.sof-data',
		   'sfs' => 'application/vnd.spotfire.sfs',
		   'sfv' => 'text/x-sfv',
		   'sgi' => 'image/sgi',
		   'sgl' => 'application/vnd.stardivision.writer-global',
		   'sgm' => 'text/sgml',
		   'sgml' => 'text/sgml',
		   'sh' => 'application/x-sh',
		   'shar' => 'application/x-shar',
		   'shf' => 'application/shf+xml',
		   'sid' => 'image/x-mrsid-image',
		   'sig' => 'application/pgp-signature',
		   'sil' => 'audio/silk',
		   'silo' => 'model/mesh',
		   'sis' => 'application/vnd.symbian.install',
		   'sisx' => 'application/vnd.symbian.install',
		   'sit' => 'application/x-stuffit',
		   'sitx' => 'application/x-stuffitx',
		   'skd' => 'application/vnd.koan',
		   'skm' => 'application/vnd.koan',
		   'skp' => 'application/vnd.koan',
		   'skt' => 'application/vnd.koan',
		   'sldm' => 'application/vnd.ms-powerpoint.slide.macroenabled.12',
		   'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		   'slt' => 'application/vnd.epson.salt',
		   'sm' => 'application/vnd.stepmania.stepchart',
		   'smf' => 'application/vnd.stardivision.math',
		   'smi' => 'application/smil+xml',
		   'smil' => 'application/smil+xml',
		   'smv' => 'video/x-smv',
		   'smzip' => 'application/vnd.stepmania.package',
		   'snd' => 'audio/basic',
		   'snf' => 'application/x-font-snf',
		   'so' => 'application/octet-stream',
		   'spc' => 'application/x-pkcs7-certificates',
		   'spf' => 'application/vnd.yamaha.smaf-phrase',
		   'spl' => 'application/x-futuresplash',
		   'spot' => 'text/vnd.in3d.spot',
		   'spp' => 'application/scvp-vp-response',
		   'spq' => 'application/scvp-vp-request',
		   'spx' => 'audio/ogg',
		   'sql' => 'application/x-sql',
		   'src' => 'application/x-wais-source',
		   'srt' => 'application/x-subrip',
		   'sru' => 'application/sru+xml',
		   'srx' => 'application/sparql-results+xml',
		   'ssdl' => 'application/ssdl+xml',
		   'sse' => 'application/vnd.kodak-descriptor',
		   'ssf' => 'application/vnd.epson.ssf',
		   'ssml' => 'application/ssml+xml',
		   'st' => 'application/vnd.sailingtracker.track',
		   'stc' => 'application/vnd.sun.xml.calc.template',
		   'std' => 'application/vnd.sun.xml.draw.template',
		   'stf' => 'application/vnd.wt.stf',
		   'sti' => 'application/vnd.sun.xml.impress.template',
		   'stk' => 'application/hyperstudio',
		   'stl' => 'application/vnd.ms-pki.stl',
		   'str' => 'application/vnd.pg.format',
		   'stw' => 'application/vnd.sun.xml.writer.template',
		   'sub' => 'image/vnd.dvb.subtitle',
		   'sub' => 'text/vnd.dvb.subtitle',
		   'sus' => 'application/vnd.sus-calendar',
		   'susp' => 'application/vnd.sus-calendar',
		   'sv4cpio' => 'application/x-sv4cpio',
		   'sv4crc' => 'application/x-sv4crc',
		   'svc' => 'application/vnd.dvb.service',
		   'svd' => 'application/vnd.svd',
		   'svg' => 'image/svg+xml',
		   'svgz' => 'image/svg+xml',
		   'swa' => 'application/x-director',
		   'swf' => 'application/x-shockwave-flash',
		   'swi' => 'application/vnd.aristanetworks.swi',
		   'sxc' => 'application/vnd.sun.xml.calc',
		   'sxd' => 'application/vnd.sun.xml.draw',
		   'sxg' => 'application/vnd.sun.xml.writer.global',
		   'sxi' => 'application/vnd.sun.xml.impress',
		   'sxm' => 'application/vnd.sun.xml.math',
		   'sxw' => 'application/vnd.sun.xml.writer',
		   't' => 'text/troff',
		   't3' => 'application/x-t3vm-image',
		   'taglet' => 'application/vnd.mynfc',
		   'tao' => 'application/vnd.tao.intent-module-archive',
		   'tar' => 'application/x-tar',
		   'tcap' => 'application/vnd.3gpp2.tcap',
		   'tcl' => 'application/x-tcl',
		   'teacher' => 'application/vnd.smart.teacher',
		   'tei' => 'application/tei+xml',
		   'teicorpus' => 'application/tei+xml',
		   'tex' => 'application/x-tex',
		   'texi' => 'application/x-texinfo',
		   'texinfo' => 'application/x-texinfo',
		   'text' => 'text/plain',
		   'tfi' => 'application/thraud+xml',
		   'tfm' => 'application/x-tex-tfm',
		   'tga' => 'image/x-tga',
		   'thmx' => 'application/vnd.ms-officetheme',
		   'tif' => 'image/tiff',
		   'tiff' => 'image/tiff',
		   'tmo' => 'application/vnd.tmobile-livetv',
		   'torrent' => 'application/x-bittorrent',
		   'tpl' => 'application/vnd.groove-tool-template',
		   'tpt' => 'application/vnd.trid.tpt',
		   'tr' => 'text/troff',
		   'tra' => 'application/vnd.trueapp',
		   'trm' => 'application/x-msterminal',
		   'tsd' => 'application/timestamped-data',
		   'tsv' => 'text/tab-separated-values',
		   'ttc' => 'font/collection',
		   'ttf' => 'font/ttf',
		   'ttl' => 'text/turtle',
		   'twd' => 'application/vnd.simtech-mindmapper',
		   'twds' => 'application/vnd.simtech-mindmapper',
		   'txd' => 'application/vnd.genomatix.tuxedo',
		   'txf' => 'application/vnd.mobius.txf',
		   'txt' => 'text/plain',
		   'u32' => 'application/x-authorware-bin',
		   'udeb' => 'application/x-debian-package',
		   'ufd' => 'application/vnd.ufdl',
		   'ufdl' => 'application/vnd.ufdl',
		   'ulx' => 'application/x-glulx',
		   'umj' => 'application/vnd.umajin',
		   'unityweb' => 'application/vnd.unity',
		   'uoml' => 'application/vnd.uoml+xml',
		   'uri' => 'text/uri-list',
		   'uris' => 'text/uri-list',
		   'urls' => 'text/uri-list',
		   'ustar' => 'application/x-ustar',
		   'utz' => 'application/vnd.uiq.theme',
		   'uu' => 'text/x-uuencode',
		   'uva' => 'audio/vnd.dece.audio',
		   'uvd' => 'application/vnd.dece.data',
		   'uvf' => 'application/vnd.dece.data',
		   'uvg' => 'image/vnd.dece.graphic',
		   'uvh' => 'video/vnd.dece.hd',
		   'uvi' => 'image/vnd.dece.graphic',
		   'uvm' => 'video/vnd.dece.mobile',
		   'uvp' => 'video/vnd.dece.pd',
		   'uvs' => 'video/vnd.dece.sd',
		   'uvt' => 'application/vnd.dece.ttml+xml',
		   'uvu' => 'video/vnd.uvvu.mp4',
		   'uvv' => 'video/vnd.dece.video',
		   'uvva' => 'audio/vnd.dece.audio',
		   'uvvd' => 'application/vnd.dece.data',
		   'uvvf' => 'application/vnd.dece.data',
		   'uvvg' => 'image/vnd.dece.graphic',
		   'uvvh' => 'video/vnd.dece.hd',
		   'uvvi' => 'image/vnd.dece.graphic',
		   'uvvm' => 'video/vnd.dece.mobile',
		   'uvvp' => 'video/vnd.dece.pd',
		   'uvvs' => 'video/vnd.dece.sd',
		   'uvvt' => 'application/vnd.dece.ttml+xml',
		   'uvvu' => 'video/vnd.uvvu.mp4',
		   'uvvv' => 'video/vnd.dece.video',
		   'uvvx' => 'application/vnd.dece.unspecified',
		   'uvvz' => 'application/vnd.dece.zip',
		   'uvx' => 'application/vnd.dece.unspecified',
		   'uvz' => 'application/vnd.dece.zip',
		   'vcard' => 'text/vcard',
		   'vcd' => 'application/x-cdlink',
		   'vcf' => 'text/x-vcard',
		   'vcg' => 'application/vnd.groove-vcard',
		   'vcs' => 'text/x-vcalendar',
		   'vcx' => 'application/vnd.vcx',
		   'vis' => 'application/vnd.visionary',
		   'viv' => 'video/vnd.vivo',
		   'vob' => 'video/x-ms-vob',
		   'vor' => 'application/vnd.stardivision.writer',
		   'vox' => 'application/x-authorware-bin',
		   'vrml' => 'model/vrml',
		   'vsd' => 'application/vnd.visio',
		   'vsf' => 'application/vnd.vsf',
		   'vss' => 'application/vnd.visio',
		   'vst' => 'application/vnd.visio',
		   'vsw' => 'application/vnd.visio',
		   'vtu' => 'model/vnd.vtu',
		   'vxml' => 'application/voicexml+xml',
		   'w3d' => 'application/x-director',
		   'wad' => 'application/x-doom',
		   'wav' => 'audio/x-wav',
		   'wax' => 'audio/x-ms-wax',
		   'wbmp' => 'image/vnd.wap.wbmp',
		   'wbs' => 'application/vnd.criticaltools.wbs+xml',
		   'wbxml' => 'application/vnd.wap.wbxml',
		   'wcm' => 'application/vnd.ms-works',
		   'wdb' => 'application/vnd.ms-works',
		   'wdp' => 'image/vnd.ms-photo',
		   'weba' => 'audio/webm',
		   'webm' => 'video/webm',
		   'webp' => 'image/webp',
		   'wg' => 'application/vnd.pmi.widget',
		   'wgt' => 'application/widget',
		   'wks' => 'application/vnd.ms-works',
		   'wm' => 'video/x-ms-wm',
		   'wma' => 'audio/x-ms-wma',
		   'wmd' => 'application/x-ms-wmd',
		   'wmf' => 'application/x-msmetafile',
		   'wml' => 'text/vnd.wap.wml',
		   'wmlc' => 'application/vnd.wap.wmlc',
		   'wmls' => 'text/vnd.wap.wmlscript',
		   'wmlsc' => 'application/vnd.wap.wmlscriptc',
		   'wmv' => 'video/x-ms-wmv',
		   'wmx' => 'video/x-ms-wmx',
		   'wmz' => 'application/x-ms-wmz',
		   'wmz' => 'application/x-msmetafile',
		   'woff' => 'font/woff',
		   'woff2' => 'font/woff2',
		   'wpd' => 'application/vnd.wordperfect',
		   'wpl' => 'application/vnd.ms-wpl',
		   'wps' => 'application/vnd.ms-works',
		   'wqd' => 'application/vnd.wqd',
		   'wri' => 'application/x-mswrite',
		   'wrl' => 'model/vrml',
		   'wsdl' => 'application/wsdl+xml',
		   'wspolicy' => 'application/wspolicy+xml',
		   'wtb' => 'application/vnd.webturbo',
		   'wvx' => 'video/x-ms-wvx',
		   'x32' => 'application/x-authorware-bin',
		   'x3d' => 'model/x3d+xml',
		   'x3db' => 'model/x3d+binary',
		   'x3dbz' => 'model/x3d+binary',
		   'x3dv' => 'model/x3d+vrml',
		   'x3dvz' => 'model/x3d+vrml',
		   'x3dz' => 'model/x3d+xml',
		   'xaml' => 'application/xaml+xml',
		   'xap' => 'application/x-silverlight-app',
		   'xar' => 'application/vnd.xara',
		   'xbap' => 'application/x-ms-xbap',
		   'xbd' => 'application/vnd.fujixerox.docuworks.binder',
		   'xbm' => 'image/x-xbitmap',
		   'xdf' => 'application/xcap-diff+xml',
		   'xdm' => 'application/vnd.syncml.dm+xml',
		   'xdp' => 'application/vnd.adobe.xdp+xml',
		   'xdssc' => 'application/dssc+xml',
		   'xdw' => 'application/vnd.fujixerox.docuworks',
		   'xenc' => 'application/xenc+xml',
		   'xer' => 'application/patch-ops-error+xml',
		   'xfdf' => 'application/vnd.adobe.xfdf',
		   'xfdl' => 'application/vnd.xfdl',
		   'xht' => 'application/xhtml+xml',
		   'xhtml' => 'application/xhtml+xml',
		   'xhvml' => 'application/xv+xml',
		   'xif' => 'image/vnd.xiff',
		   'xla' => 'application/vnd.ms-excel',
		   'xlam' => 'application/vnd.ms-excel.addin.macroenabled.12',
		   'xlc' => 'application/vnd.ms-excel',
		   'xlf' => 'application/x-xliff+xml',
		   'xlm' => 'application/vnd.ms-excel',
		   'xls' => 'application/vnd.ms-excel',
		   'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroenabled.12',
		   'xlsm' => 'application/vnd.ms-excel.sheet.macroenabled.12',
		   'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		   'xlt' => 'application/vnd.ms-excel',
		   'xltm' => 'application/vnd.ms-excel.template.macroenabled.12',
		   'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		   'xlw' => 'application/vnd.ms-excel',
		   'xm' => 'audio/xm',
		   'xml' => 'application/xml',
		   'xo' => 'application/vnd.olpc-sugar',
		   'xop' => 'application/xop+xml',
		   'xpi' => 'application/x-xpinstall',
		   'xpl' => 'application/xproc+xml',
		   'xpm' => 'image/x-xpixmap',
		   'xpr' => 'application/vnd.is-xpr',
		   'xps' => 'application/vnd.ms-xpsdocument',
		   'xpw' => 'application/vnd.intercon.formnet',
		   'xpx' => 'application/vnd.intercon.formnet',
		   'xsl' => 'application/xml',
		   'xslt' => 'application/xslt+xml',
		   'xsm' => 'application/vnd.syncml+xml',
		   'xspf' => 'application/xspf+xml',
		   'xul' => 'application/vnd.mozilla.xul+xml',
		   'xvm' => 'application/xv+xml',
		   'xvml' => 'application/xv+xml',
		   'xwd' => 'image/x-xwindowdump',
		   'xyz' => 'chemical/x-xyz',
		   'xz' => 'application/x-xz',
		   'yang' => 'application/yang',
		   'yin' => 'application/yin+xml',
		   'z1' => 'application/x-zmachine',
		   'z2' => 'application/x-zmachine',
		   'z3' => 'application/x-zmachine',
		   'z4' => 'application/x-zmachine',
		   'z5' => 'application/x-zmachine',
		   'z6' => 'application/x-zmachine',
		   'z7' => 'application/x-zmachine',
		   'z8' => 'application/x-zmachine',
		   'zaz' => 'application/vnd.zzazz.deck+xml',
		   'zip' => 'application/zip',
		   'zir' => 'application/vnd.zul',
		   'zirz' => 'application/vnd.zul',
		   'zmm' => 'application/vnd.handheld-entertainment+xml'
		);
        
        if (is_null($ext))
            return $extension_mime;
        
        $lower_ext = strtolower($ext);
        
        return isset($extension_mime[$lower_ext]) ? $extension_mime[$lower_ext] : null;
    }
    /**
     * Detect MIME Content-type for a file
     *
     * @param string $filename Path to the tested file.
     * @return string
     */
    public function contentType($filename)
    {
        $ext = $this->fileExtension($filename);
        /* strtolower isn't necessary */
        if ($mime = $this->fileMimeType($ext))
            return $mime;
        return 'application/octet-stream';
    }
    /**
     * Returns file extension or false if none
     *
     * @param string $filename 
     * @return string, false
     */
    public function fileExtension($filename)
    {
        $pos = strrpos($filename, '.');
        if ($pos !== false)
            return substr($filename, $pos + 1);
        return false;
    }
    /**
     * Checks if $filename is a image file
     *
     * @return bool
     */
	public function isImage(){
		
		return in_array($this->ext, $this->image_exts);
	}
    /**
     * Checks if $filename is an audio file
     *
     * @return bool
     */
	public function isAudio(){
		return in_array($this->ext, $this->audio_exts);
	}
	
    /**
     * Checks if $filename is a video file
     *
     * @return bool
     */
	public function isVideo(){
		return in_array($this->ext, $this->video_exts);
	}

    
    /**
     * Checks if $filename is a text file
     *
     * @param string $filename 
     * @return bool
     */
    public function isText()
    {
        if($this->getAceModeForPath()){
			return true;
		}elseif ($mime = $this->contentType($this->filename)){
			return substr($mime, 0, 5) == "text/";
		}
            
        return null;
    }
    
    /**
     * Checks if $filename is a binary file
     *
     * @param string $filename 
     * @return void
     */
    public function isBinary()
    {
        return (!$this->isText() && !$this->isImage() && !$this->isVideo() && !$this->isAudio());
    }
    public function getContentType()
    {
        return $this->contentType($this->filename);
    }
    public function getExtension()
    {
        return $this->fileExtension($this->filename);
    }
    public function getFilename()
    {
        return $this->filename;
    }
    public function getAceModeForPath()
    {
        $mode = null;
		static $supported_ace_modes = array(
			"abap" => array(
				"name" => "abap",
				"caption" => "ABAP",
				"mode" => "ace/mode/abap",
				"extRe" => "/^.*\.(abap)$/i"
			),
			"abc" => array(
				"name" => "abc",
				"caption" => "ABC",
				"mode" => "ace/mode/abc",
				"extRe" => "/^.*\.(abc)$/i"
			),
			"actionscript" => array(
				"name" => "actionscript",
				"caption" => "ActionScript",
				"mode" => "ace/mode/actionscript",
				"extRe" => "/^.*\.(as)$/i"
			),
			"ada" => array(
				"name" => "ada",
				"caption" => "ADA",
				"mode" => "ace/mode/ada",
				"extRe" => "/^.*\.(ada|adb)$/i"
			),
			"apache_conf" => array(
				"name" => "apache_conf",
				"caption" => "Apache Conf",
				"mode" => "ace/mode/apache_conf",
				"extRe" => "/^htaccess$|^htgroups$|^htpasswd$|^conf$|^.*\.htaccess$|^.*\.htgroups$|^.*\.htpasswd$/i"
			),
			"asciidoc" => array(
				"name" => "asciidoc",
				"caption" => "AsciiDoc",
				"mode" => "ace/mode/asciidoc",
				"extRe" => "/^.*\.(asciidoc|adoc)$/i"
			),
			"asl" => array(
				"name" => "asl",
				"caption" => "ASL",
				"mode" => "ace/mode/asl",
				"extRe" => "/^.*\.(dsl|asl)$/i"
			),
			"assembly_x86" => array(
				"name" => "assembly_x86",
				"caption" => "Assembly x86",
				"mode" => "ace/mode/assembly_x86",
				"extRe" => "/^.*\.(asm|a)$/i"
			),
			"autohotkey" => array(
				"name" => "autohotkey",
				"caption" => "AutoHotKey",
				"mode" => "ace/mode/autohotkey",
				"extRe" => "/^.*\.(ahk)$/i"
			),
			"batchfile" => array(
				"name" => "batchfile",
				"caption" => "BatchFile",
				"mode" => "ace/mode/batchfile",
				"extRe" => "/^.*\.(bat|cmd)$/i"
			),
			"bro" => array(
				"name" => "bro",
				"caption" => "Bro",
				"mode" => "ace/mode/bro",
				"extRe" => "/^.*\.(bro)$/i"
			),
			"c_cpp" => array(
				"name" => "c_cpp",
				"caption" => "C and C++",
				"mode" => "ace/mode/c_cpp",
				"extRe" => "/^.*\.(cpp|c|cc|cxx|h|hh|hpp|ino)$/i"
			),
			"c9search" => array(
				"name" => "c9search",
				"caption" => "C9Search",
				"mode" => "ace/mode/c9search",
				"extRe" => "/^.*\.(c9search_results)$/i"
			),
			"cirru" => array(
				"name" => "cirru",
				"caption" => "Cirru",
				"mode" => "ace/mode/cirru",
				"extRe" => "/^.*\.(cirru|cr)$/i"
			),
			"clojure" => array(
				"name" => "clojure",
				"caption" => "Clojure",
				"mode" => "ace/mode/clojure",
				"extRe" => "/^.*\.(clj|cljs)$/i"
			),
			"cobol" => array(
				"name" => "cobol",
				"caption" => "Cobol",
				"mode" => "ace/mode/cobol",
				"extRe" => "/^.*\.(CBL|COB)$/i"
			),
			"coffee" => array(
				"name" => "coffee",
				"caption" => "CoffeeScript",
				"mode" => "ace/mode/coffee",
				"extRe" => "/coffee$|^.*\.cf$|^.*\.cson$|^Cakefile$/i"
			),
			"coldfusion" => array(
				"name" => "coldfusion",
				"caption" => "ColdFusion",
				"mode" => "ace/mode/coldfusion",
				"extRe" => "/^.*\.(cfm)$/i"
			),
			"csharp" => array(
				"name" => "csharp",
				"caption" => "C#",
				"mode" => "ace/mode/csharp",
				"extRe" => "/^.*\.(cs)$/i"
			),
			"csound_document" => array(
				"name" => "csound_document",
				"caption" => "Csound Document",
				"mode" => "ace/mode/csound_document",
				"extRe" => "/^.*\.(csd)$/i"
			),
			"csound_orchestra" => array(
				"name" => "csound_orchestra",
				"caption" => "Csound",
				"mode" => "ace/mode/csound_orchestra",
				"extRe" => "/^.*\.(orc)$/i"
			),
			"csound_score" => array(
				"name" => "csound_score",
				"caption" => "Csound Score",
				"mode" => "ace/mode/csound_score",
				"extRe" => "/^.*\.(sco)$/i"
			),
			"css" => array(
				"name" => "css",
				"caption" => "CSS",
				"mode" => "ace/mode/css",
				"extRe" => "/^.*\.(css)$/i"
			),
			"curly" => array(
				"name" => "curly",
				"caption" => "Curly",
				"mode" => "ace/mode/curly",
				"extRe" => "/^.*\.(curly)$/i"
			),
			"d" => array(
				"name" => "d",
				"caption" => "D",
				"mode" => "ace/mode/d",
				"extRe" => "/^.*\.(d|di)$/i"
			),
			"dart" => array(
				"name" => "dart",
				"caption" => "Dart",
				"mode" => "ace/mode/dart",
				"extRe" => "/^.*\.(dart)$/i"
			),
			"diff" => array(
				"name" => "diff",
				"caption" => "Diff",
				"mode" => "ace/mode/diff",
				"extRe" => "/^.*\.(diff|patch)$/i"
			),
			"dockerfile" => array(
				"name" => "dockerfile",
				"caption" => "Dockerfile",
				"mode" => "ace/mode/dockerfile",
				"extRe" => "/^Dockerfile$/i"
			),
			"dot" => array(
				"name" => "dot",
				"caption" => "Dot",
				"mode" => "ace/mode/dot",
				"extRe" => "/^.*\.(dot)$/i"
			),
			"drools" => array(
				"name" => "drools",
				"caption" => "Drools",
				"mode" => "ace/mode/drools",
				"extRe" => "/^.*\.(drl)$/i"
			),
			"edifact" => array(
				"name" => "edifact",
				"caption" => "Edifact",
				"mode" => "ace/mode/edifact",
				"extRe" => "/^.*\.(edi)$/i"
			),
			"eiffel" => array(
				"name" => "eiffel",
				"caption" => "Eiffel",
				"mode" => "ace/mode/eiffel",
				"extRe" => "/^.*\.(e|ge)$/i"
			),
			"ejs" => array(
				"name" => "ejs",
				"caption" => "EJS",
				"mode" => "ace/mode/ejs",
				"extRe" => "/^.*\.(ejs)$/i"
			),
			"elixir" => array(
				"name" => "elixir",
				"caption" => "Elixir",
				"mode" => "ace/mode/elixir",
				"extRe" => "/^.*\.(ex|exs)$/i"
			),
			"elm" => array(
				"name" => "elm",
				"caption" => "Elm",
				"mode" => "ace/mode/elm",
				"extRe" => "/^.*\.(elm)$/i"
			),
			"erlang" => array(
				"name" => "erlang",
				"caption" => "Erlang",
				"mode" => "ace/mode/erlang",
				"extRe" => "/^.*\.(erl|hrl)$/i"
			),
			"forth" => array(
				"name" => "forth",
				"caption" => "Forth",
				"mode" => "ace/mode/forth",
				"extRe" => "/^.*\.(frt|fs|ldr|fth|4th)$/i"
			),
			"fortran" => array(
				"name" => "fortran",
				"caption" => "Fortran",
				"mode" => "ace/mode/fortran",
				"extRe" => "/^.*\.(f|f90)$/i"
			),
			"fsharp" => array(
				"name" => "fsharp",
				"caption" => "FSharp",
				"mode" => "ace/mode/fsharp",
				"extRe" => "/^.*\.(fsi|fs|ml|mli|fsx|fsscript)$/i"
			),
			"ftl" => array(
				"name" => "ftl",
				"caption" => "FreeMarker",
				"mode" => "ace/mode/ftl",
				"extRe" => "/^.*\.(ftl)$/i"
			),
			"gcode" => array(
				"name" => "gcode",
				"caption" => "Gcode",
				"mode" => "ace/mode/gcode",
				"extRe" => "/^.*\.(gcode)$/i"
			),
			"gherkin" => array(
				"name" => "gherkin",
				"caption" => "Gherkin",
				"mode" => "ace/mode/gherkin",
				"extRe" => "/^.*\.(feature)$/i"
			),
			"gitignore" => array(
				"name" => "gitignore",
				"caption" => "Gitignore",
				"mode" => "ace/mode/itignore",
				"extRe" => "/^.gitignore$/i"
			),
			"glsl" => array(
				"name" => "glsl",
				"caption" => "Glsl",
				"mode" => "ace/mode/glsl",
				"extRe" => "/^.*\.(glsl|frag|vert)$/i"
			),
			"gobstones" => array(
				"name" => "gobstones",
				"caption" => "Gobstones",
				"mode" => "ace/mode/gobstones",
				"extRe" => "/^.*\.(gbs)$/i"
			),
			"golang" => array(
				"name" => "golang",
				"caption" => "Go",
				"mode" => "ace/mode/golang",
				"extRe" => "/^.*\.(go)$/i"
			),
			"graphqlschema" => array(
				"name" => "graphqlschema",
				"caption" => "GraphQLSchema",
				"mode" => "ace/mode/graphqlschema",
				"extRe" => "/^.*\.(gql)$/i"
			),
			"groovy" => array(
				"name" => "groovy",
				"caption" => "Groovy",
				"mode" => "ace/mode/groovy",
				"extRe" => "/^.*\.(groovy)$/i"
			),
			"haml" => array(
				"name" => "haml",
				"caption" => "HAML",
				"mode" => "ace/mode/haml",
				"extRe" => "/^.*\.(haml)$/i"
			),
			"handlebars" => array(
				"name" => "handlebars",
				"caption" => "Handlebars",
				"mode" => "ace/mode/handlebars",
				"extRe" => "/^.*\.(hbs|handlebars|tpl|mustache)$/i"
			),
			"haskell" => array(
				"name" => "haskell",
				"caption" => "Haskell",
				"mode" => "ace/mode/haskell",
				"extRe" => "/^.*\.(hs)$/i"
			),
			"haskell_cabal" => array(
				"name" => "haskell_cabal",
				"caption" => "Haskell Cabal",
				"mode" => "ace/mode/haskell_cabal",
				"extRe" => "/^.*\.(cabal)$/i"
			),
			"haxe" => array(
				"name" => "haxe",
				"caption" => "haXe",
				"mode" => "ace/mode/haxe",
				"extRe" => "/^.*\.(hx)$/i"
			),
			"hjson" => array(
				"name" => "hjson",
				"caption" => "Hjson",
				"mode" => "ace/mode/hjson",
				"extRe" => "/^.*\.(hjson)$/i"
			),
			"html" => array(
				"name" => "html",
				"caption" => "HTML",
				"mode" => "ace/mode/html",
				"extRe" => "/^.*\.(html|htm|xhtml|vue|we|wpy)$/i"
			),
			"html_elixir" => array(
				"name" => "html_elixir",
				"caption" => "HTML (Elixir)",
				"mode" => "ace/mode/html_elixir",
				"extRe" => "/^.*\.(eex|html.eex)$/i"
			),
			"html_ruby" => array(
				"name" => "html_ruby",
				"caption" => "HTML (Ruby)",
				"mode" => "ace/mode/html_ruby",
				"extRe" => "/^.*\.(erb|rhtml|html.erb)$/i"
			),
			"ini" => array(
				"name" => "ini",
				"caption" => "INI",
				"mode" => "ace/mode/ini",
				"extRe" => "/^.*\.(ini|conf|cfg|prefs)$/i"
			),
			"io" => array(
				"name" => "io",
				"caption" => "Io",
				"mode" => "ace/mode/io",
				"extRe" => "/^.*\.(io)$/i"
			),
			"jack" => array(
				"name" => "jack",
				"caption" => "Jack",
				"mode" => "ace/mode/jack",
				"extRe" => "/^.*\.(jack)$/i"
			),
			"jade" => array(
				"name" => "jade",
				"caption" => "Jade",
				"mode" => "ace/mode/jade",
				"extRe" => "/^.*\.(jade|pug)$/i"
			),
			"java" => array(
				"name" => "java",
				"caption" => "Java",
				"mode" => "ace/mode/java",
				"extRe" => "/^.*\.(java)$/i"
			),
			"javascript" => array(
				"name" => "javascript",
				"caption" => "JavaScript",
				"mode" => "ace/mode/javascript",
				"extRe" => "/^.*\.(js|jsm|jsx)$/i"
			),
			"json" => array(
				"name" => "json",
				"caption" => "JSON",
				"mode" => "ace/mode/json",
				"extRe" => "/^.*\.(json)$/i"
			),
			"jsoniq" => array(
				"name" => "jsoniq",
				"caption" => "JSONiq",
				"mode" => "ace/mode/jsoniq",
				"extRe" => "/^.*\.(jq)$/i"
			),
			"jsp" => array(
				"name" => "jsp",
				"caption" => "JSP",
				"mode" => "ace/mode/jsp",
				"extRe" => "/^.*\.(jsp)$/i"
			),
			"jssm" => array(
				"name" => "jssm",
				"caption" => "JSSM",
				"mode" => "ace/mode/jssm",
				"extRe" => "/^.*\.(jssm|jssm_state)$/i"
			),
			"jsx" => array(
				"name" => "jsx",
				"caption" => "JSX",
				"mode" => "ace/mode/jsx",
				"extRe" => "/^.*\.(jsx)$/i"
			),
			"julia" => array(
				"name" => "julia",
				"caption" => "Julia",
				"mode" => "ace/mode/julia",
				"extRe" => "/^.*\.(jl)$/i"
			),
			"kotlin" => array(
				"name" => "kotlin",
				"caption" => "Kotlin",
				"mode" => "ace/mode/kotlin",
				"extRe" => "/^.*\.(kt|kts)$/i"
			),
			"latex" => array(
				"name" => "latex",
				"caption" => "LaTeX",
				"mode" => "ace/mode/latex",
				"extRe" => "/^.*\.(tex|latex|ltx|bib)$/i"
			),
			"less" => array(
				"name" => "less",
				"caption" => "LESS",
				"mode" => "ace/mode/less",
				"extRe" => "/^.*\.(less)$/i"
			),
			"liquid" => array(
				"name" => "liquid",
				"caption" => "Liquid",
				"mode" => "ace/mode/liquid",
				"extRe" => "/^.*\.(liquid)$/i"
			),
			"lisp" => array(
				"name" => "lisp",
				"caption" => "Lisp",
				"mode" => "ace/mode/lisp",
				"extRe" => "/^.*\.(lisp)$/i"
			),
			"livescript" => array(
				"name" => "livescript",
				"caption" => "LiveScript",
				"mode" => "ace/mode/livescript",
				"extRe" => "/^.*\.(ls)$/i"
			),
			"logiql" => array(
				"name" => "logiql",
				"caption" => "LogiQL",
				"mode" => "ace/mode/logiql",
				"extRe" => "/^.*\.(logic|lql)$/i"
			),
			"lsl" => array(
				"name" => "lsl",
				"caption" => "LSL",
				"mode" => "ace/mode/lsl",
				"extRe" => "/^.*\.(lsl)$/i"
			),
			"lua" => array(
				"name" => "lua",
				"caption" => "Lua",
				"mode" => "ace/mode/lua",
				"extRe" => "/^.*\.(lua)$/i"
			),
			"luapage" => array(
				"name" => "luapage",
				"caption" => "LuaPage",
				"mode" => "ace/mode/luapage",
				"extRe" => "/^.*\.(lp)$/i"
			),
			"lucene" => array(
				"name" => "lucene",
				"caption" => "Lucene",
				"mode" => "ace/mode/lucene",
				"extRe" => "/^.*\.(lucene)$/i"
			),
			"makefile" => array(
				"name" => "makefile",
				"caption" => "Makefile",
				"mode" => "ace/mode/makefile",
				"extRe" => "/^Makefile$|^GNUmakefile$|^makefile$|^OCamlMakefile$|^.*\.make$/i"
			),
			"markdown" => array(
				"name" => "markdown",
				"caption" => "Markdown",
				"mode" => "ace/mode/markdown",
				"extRe" => "/^.*\.(md|markdown)$/i"
			),
			"mask" => array(
				"name" => "mask",
				"caption" => "Mask",
				"mode" => "ace/mode/mask",
				"extRe" => "/^.*\.(mask)$/i"
			),
			"matlab" => array(
				"name" => "matlab",
				"caption" => "MATLAB",
				"mode" => "ace/mode/matlab",
				"extRe" => "/^.*\.(matlab)$/i"
			),
			"maze" => array(
				"name" => "maze",
				"caption" => "Maze",
				"mode" => "ace/mode/maze",
				"extRe" => "/^.*\.(mz)$/i"
			),
			"mel" => array(
				"name" => "mel",
				"caption" => "MEL",
				"mode" => "ace/mode/mel",
				"extRe" => "/^.*\.(mel)$/i"
			),
			"mixal" => array(
				"name" => "mixal",
				"caption" => "MIXAL",
				"mode" => "ace/mode/mixal",
				"extRe" => "/^.*\.(mixal)$/i"
			),
			"mushcode" => array(
				"name" => "mushcode",
				"caption" => "MUSHCode",
				"mode" => "ace/mode/mushcode",
				"extRe" => "/^.*\.(mc|mush)$/i"
			),
			"mysql" => array(
				"name" => "mysql",
				"caption" => "MySQL",
				"mode" => "ace/mode/mysql",
				"extRe" => "/^.*\.(mysql)$/i"
			),
			"nix" => array(
				"name" => "nix",
				"caption" => "Nix",
				"mode" => "ace/mode/nix",
				"extRe" => "/^.*\.(nix)$/i"
			),
			"nsis" => array(
				"name" => "nsis",
				"caption" => "NSIS",
				"mode" => "ace/mode/nsis",
				"extRe" => "/^.*\.(nsi|nsh)$/i"
			),
			"objectivec" => array(
				"name" => "objectivec",
				"caption" => "Objective-C",
				"mode" => "ace/mode/objectivec",
				"extRe" => "/^.*\.(m|mm)$/i"
			),
			"ocaml" => array(
				"name" => "ocaml",
				"caption" => "OCaml",
				"mode" => "ace/mode/ocaml",
				"extRe" => "/^.*\.(ml|mli)$/i"
			),
			"pascal" => array(
				"name" => "pascal",
				"caption" => "Pascal",
				"mode" => "ace/mode/pascal",
				"extRe" => "/^.*\.(pas|p)$/i"
			),
			"perl" => array(
				"name" => "perl",
				"caption" => "Perl",
				"mode" => "ace/mode/perl",
				"extRe" => "/^.*\.(pl|pm)$/i"
			),
			"pgsql" => array(
				"name" => "pgsql",
				"caption" => "pgSQL",
				"mode" => "ace/mode/pgsql",
				"extRe" => "/^.*\.(pgsql)$/i"
			),
			"php_laravel_blade" => array(
				"name" => "php_laravel_blade",
				"caption" => "PHP (Blade Template)",
				"mode" => "ace/mode/php_laravel_blade",
				"extRe" => "/^.*\.(blade.php)$/i"
			),
			"php" => array(
				"name" => "php",
				"caption" => "PHP",
				"mode" => "ace/mode/php",
				"extRe" => "/^.*\.(php|phtml|shtml|php3|php4|php5|phps|phpt|aw|ctp|module)$/i"
			),
			"puppet" => array(
				"name" => "puppet",
				"caption" => "Puppet",
				"mode" => "ace/mode/puppet",
				"extRe" => "/^.*\.(epp|pp)$/i"
			),
			"pig" => array(
				"name" => "pig",
				"caption" => "Pig",
				"mode" => "ace/mode/pig",
				"extRe" => "/^.*\.(pig)$/i"
			),
			"powershell" => array(
				"name" => "powershell",
				"caption" => "Powershell",
				"mode" => "ace/mode/powershell",
				"extRe" => "/^.*\.(ps1)$/i"
			),
			"praat" => array(
				"name" => "praat",
				"caption" => "Praat",
				"mode" => "ace/mode/praat",
				"extRe" => "/^.*\.(praat|praatscript|psc|proc)$/i"
			),
			"prolog" => array(
				"name" => "prolog",
				"caption" => "Prolog",
				"mode" => "ace/mode/prolog",
				"extRe" => "/^.*\.(plg|prolog)$/i"
			),
			"properties" => array(
				"name" => "properties",
				"caption" => "Properties",
				"mode" => "ace/mode/properties",
				"extRe" => "/^.*\.(properties)$/i"
			),
			"protobuf" => array(
				"name" => "protobuf",
				"caption" => "Protobuf",
				"mode" => "ace/mode/protobuf",
				"extRe" => "/^.*\.(proto)$/i"
			),
			"python" => array(
				"name" => "python",
				"caption" => "Python",
				"mode" => "ace/mode/python",
				"extRe" => "/^.*\.(py)$/i"
			),
			"r" => array(
				"name" => "r",
				"caption" => "R",
				"mode" => "ace/mode/r",
				"extRe" => "/^.*\.(r)$/i"
			),
			"razor" => array(
				"name" => "razor",
				"caption" => "Razor",
				"mode" => "ace/mode/razor",
				"extRe" => "/^.*\.(cshtml|asp)$/i"
			),
			"rdoc" => array(
				"name" => "rdoc",
				"caption" => "RDoc",
				"mode" => "ace/mode/rdoc",
				"extRe" => "/^.*\.(Rd)$/i"
			),
			"red" => array(
				"name" => "red",
				"caption" => "Red",
				"mode" => "ace/mode/red",
				"extRe" => "/^.*\.(red|reds)$/i"
			),
			"rhtml" => array(
				"name" => "rhtml",
				"caption" => "RHTML",
				"mode" => "ace/mode/rhtml",
				"extRe" => "/^.*\.(Rhtml)$/i"
			),
			"rst" => array(
				"name" => "rst",
				"caption" => "RST",
				"mode" => "ace/mode/rst",
				"extRe" => "/^.*\.(rst)$/i"
			),
			"ruby" => array(
				"name" => "ruby",
				"caption" => "Ruby",
				"mode" => "ace/mode/ruby",
				"extRe" => "/rb$|^.*\.ru$|^.*\.gemspec$|^.*\.rake$|^Guardfile$|^Rakefile$|^Gemfile$/i"
			),
			"rust" => array(
				"name" => "rust",
				"caption" => "Rust",
				"mode" => "ace/mode/rust",
				"extRe" => "/^.*\.(rs)$/i"
			),
			"sass" => array(
				"name" => "sass",
				"caption" => "SASS",
				"mode" => "ace/mode/sass",
				"extRe" => "/^.*\.(sass)$/i"
			),
			"scad" => array(
				"name" => "scad",
				"caption" => "SCAD",
				"mode" => "ace/mode/scad",
				"extRe" => "/^.*\.(scad)$/i"
			),
			"scala" => array(
				"name" => "scala",
				"caption" => "Scala",
				"mode" => "ace/mode/scala",
				"extRe" => "/^.*\.(scala)$/i"
			),
			"scheme" => array(
				"name" => "scheme",
				"caption" => "Scheme",
				"mode" => "ace/mode/scheme",
				"extRe" => "/^.*\.(scm|sm|rkt|oak|scheme)$/i"
			),
			"scss" => array(
				"name" => "scss",
				"caption" => "SCSS",
				"mode" => "ace/mode/scss",
				"extRe" => "/^.*\.(scss)$/i"
			),
			"sh" => array(
				"name" => "sh",
				"caption" => "SH",
				"mode" => "ace/mode/sh",
				"extRe" => "/sh$|^.*\.bash$|^.bashrc$/i"
			),
			"sjs" => array(
				"name" => "sjs",
				"caption" => "SJS",
				"mode" => "ace/mode/sjs",
				"extRe" => "/^.*\.(sjs)$/i"
			),
			"slim" => array(
				"name" => "slim",
				"caption" => "Slim",
				"mode" => "ace/mode/slim",
				"extRe" => "/^.*\.(slim|skim)$/i"
			),
			"smarty" => array(
				"name" => "smarty",
				"caption" => "Smarty",
				"mode" => "ace/mode/smarty",
				"extRe" => "/^.*\.(smarty|tpl)$/i"
			),
			"snippets" => array(
				"name" => "snippets",
				"caption" => "snippets",
				"mode" => "ace/mode/snippets",
				"extRe" => "/^.*\.(snippets)$/i"
			),
			"soy_template" => array(
				"name" => "soy_template",
				"caption" => "Soy Template",
				"mode" => "ace/mode/soy_template",
				"extRe" => "/^.*\.(soy)$/i"
			),
			"space" => array(
				"name" => "space",
				"caption" => "Space",
				"mode" => "ace/mode/space",
				"extRe" => "/^.*\.(space)$/i"
			),
			"sql" => array(
				"name" => "sql",
				"caption" => "SQL",
				"mode" => "ace/mode/sql",
				"extRe" => "/^.*\.(sql)$/i"
			),
			"sqlserver" => array(
				"name" => "sqlserver",
				"caption" => "SQLServer",
				"mode" => "ace/mode/sqlserver",
				"extRe" => "/^.*\.(sqlserver)$/i"
			),
			"stylus" => array(
				"name" => "stylus",
				"caption" => "Stylus",
				"mode" => "ace/mode/stylus",
				"extRe" => "/^.*\.(styl|stylus)$/i"
			),
			"svg" => array(
				"name" => "svg",
				"caption" => "SVG",
				"mode" => "ace/mode/svg",
				"extRe" => "/^.*\.(svg)$/i"
			),
			"swift" => array(
				"name" => "swift",
				"caption" => "Swift",
				"mode" => "ace/mode/swift",
				"extRe" => "/^.*\.(swift)$/i"
			),
			"tcl" => array(
				"name" => "tcl",
				"caption" => "Tcl",
				"mode" => "ace/mode/tcl",
				"extRe" => "/^.*\.(tcl)$/i"
			),
			"terraform" => array(
				"name" => "terraform",
				"caption" => "Terraform",
				"mode" => "ace/mode/terraform",
				"extRe" => "/^.*\.(tf)$/i"
			),
			"tex" => array(
				"name" => "tex",
				"caption" => "Tex",
				"mode" => "ace/mode/tex",
				"extRe" => "/^.*\.(tex)$/i"
			),
			"text" => array(
				"name" => "text",
				"caption" => "Text",
				"mode" => "ace/mode/text",
				"extRe" => "/^.*\.(txt)$/i"
			),
			"textile" => array(
				"name" => "textile",
				"caption" => "Textile",
				"mode" => "ace/mode/textile",
				"extRe" => "/^.*\.(textile)$/i"
			),
			"toml" => array(
				"name" => "toml",
				"caption" => "Toml",
				"mode" => "ace/mode/toml",
				"extRe" => "/^.*\.(toml)$/i"
			),
			"tsx" => array(
				"name" => "tsx",
				"caption" => "TSX",
				"mode" => "ace/mode/tsx",
				"extRe" => "/^.*\.(tsx)$/i"
			),
			"twig" => array(
				"name" => "twig",
				"caption" => "Twig",
				"mode" => "ace/mode/twig",
				"extRe" => "/^.*\.(twig|swig)$/i"
			),
			"typescript" => array(
				"name" => "typescript",
				"caption" => "Typescript",
				"mode" => "ace/mode/typescript",
				"extRe" => "/^.*\.(ts|typescript|str)$/i"
			),
			"vala" => array(
				"name" => "vala",
				"caption" => "Vala",
				"mode" => "ace/mode/vala",
				"extRe" => "/^.*\.(vala)$/i"
			),
			"vbscript" => array(
				"name" => "vbscript",
				"caption" => "VBScript",
				"mode" => "ace/mode/vbscript",
				"extRe" => "/^.*\.(vbs|vb)$/i"
			),
			"velocity" => array(
				"name" => "velocity",
				"caption" => "Velocity",
				"mode" => "ace/mode/velocity",
				"extRe" => "/^.*\.(vm)$/i"
			),
			"verilog" => array(
				"name" => "verilog",
				"caption" => "Verilog",
				"mode" => "ace/mode/verilog",
				"extRe" => "/^.*\.(v|vh|sv|svh)$/i"
			),
			"vhdl" => array(
				"name" => "vhdl",
				"caption" => "VHDL",
				"mode" => "ace/mode/vhdl",
				"extRe" => "/^.*\.(vhd|vhdl)$/i"
			),
			"wollok" => array(
				"name" => "wollok",
				"caption" => "Wollok",
				"mode" => "ace/mode/wollok",
				"extRe" => "/^.*\.(wlk|wpgm|wtest)$/i"
			),
			"xml" => array(
				"name" => "xml",
				"caption" => "XML",
				"mode" => "ace/mode/xml",
				"extRe" => "/^.*\.(xml|rdf|rss|wsdl|xslt|atom|mathml|mml|xul|xbl|xaml)$/i"
			),
			"xquery" => array(
				"name" => "xquery",
				"caption" => "XQuery",
				"mode" => "ace/mode/xquery",
				"extRe" => "/^.*\.(xq)$/i"
			),
			"yaml" => array(
				"name" => "yaml",
				"caption" => "YAML",
				"mode" => "ace/mode/yaml",
				"extRe" => "/^.*\.(yaml|yml)$/i"
			)
		);
        foreach($supported_ace_modes as $supported){
            if(preg_match($supported['extRe'],$this->filename)){
                $mode = $supported;
                break;
            }
        }
		if($mode === null && ($this->filename === 'README' || $this->filename === 'readme')){
			$mode = $supported_ace_modes['markdown'];
		}
        return $mode;
    }

}