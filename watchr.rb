##
 # @author David Rogers <david@ethos-development.com>
##

def test ( filename )
    filename.gsub!(/^ethos/,'Test')
    filename.gsub!(/\.php/,'Test.php') unless filename.match /Test\.php/
    system 'clear; ./test.sh --verbose ' + filename
end #test

watch ( '(ethos|Test)/.*\.php$' ){|matches| test(matches[0]); }

