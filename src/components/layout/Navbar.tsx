import React from "react";
import { Link } from "react-router-dom";
import { Bitcoin, Menu, X } from "lucide-react";
import { Button } from "@/components/ui/button";

interface NavbarProps {
  onLoginClick?: () => void;
  onSignupClick?: () => void;
}

const Navbar = ({
  onLoginClick = () => {},
  onSignupClick = () => {},
}: NavbarProps) => {
  const [mobileMenuOpen, setMobileMenuOpen] = React.useState(false);

  const toggleMobileMenu = () => {
    setMobileMenuOpen(!mobileMenuOpen);
  };

  return (
    <nav className="w-full h-20 bg-white border-b border-gray-200 shadow-sm fixed top-0 left-0 z-50">
      <div className="container mx-auto h-full px-4 flex items-center justify-between">
        {/* Logo and Platform Name */}
        <Link to="/" className="flex items-center space-x-2">
          <Bitcoin className="h-8 w-8 text-primary" />
          <span className="font-bold text-xl">CryptoTrade</span>
        </Link>

        {/* Desktop Navigation */}
        <div className="hidden md:flex items-center space-x-8">
          <div className="flex items-center space-x-6">
            <Link
              to="/"
              className="text-gray-700 hover:text-primary transition-colors"
            >
              Home
            </Link>
            <Link
              to="/markets"
              className="text-gray-700 hover:text-primary transition-colors"
            >
              Markets
            </Link>
            <Link
              to="/features"
              className="text-gray-700 hover:text-primary transition-colors"
            >
              Features
            </Link>
            <Link
              to="/about"
              className="text-gray-700 hover:text-primary transition-colors"
            >
              About
            </Link>
          </div>

          <div className="flex items-center space-x-3">
            <Button variant="outline" onClick={onLoginClick}>
              Login
            </Button>
            <Button onClick={onSignupClick}>Sign Up</Button>
          </div>
        </div>

        {/* Mobile Menu Button */}
        <div className="md:hidden">
          <Button variant="ghost" size="icon" onClick={toggleMobileMenu}>
            {mobileMenuOpen ? (
              <X className="h-6 w-6" />
            ) : (
              <Menu className="h-6 w-6" />
            )}
          </Button>
        </div>
      </div>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <div className="md:hidden absolute top-20 left-0 w-full bg-white border-b border-gray-200 shadow-md py-4 px-4">
          <div className="flex flex-col space-y-4">
            <Link
              to="/"
              className="text-gray-700 hover:text-primary transition-colors py-2"
              onClick={() => setMobileMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              to="/markets"
              className="text-gray-700 hover:text-primary transition-colors py-2"
              onClick={() => setMobileMenuOpen(false)}
            >
              Markets
            </Link>
            <Link
              to="/features"
              className="text-gray-700 hover:text-primary transition-colors py-2"
              onClick={() => setMobileMenuOpen(false)}
            >
              Features
            </Link>
            <Link
              to="/about"
              className="text-gray-700 hover:text-primary transition-colors py-2"
              onClick={() => setMobileMenuOpen(false)}
            >
              About
            </Link>
            <div className="flex flex-col space-y-2 pt-2 border-t border-gray-100">
              <Button
                variant="outline"
                onClick={onLoginClick}
                className="w-full justify-center"
              >
                Login
              </Button>
              <Button onClick={onSignupClick} className="w-full justify-center">
                Sign Up
              </Button>
            </div>
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
