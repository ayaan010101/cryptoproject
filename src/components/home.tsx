import React, { useState } from "react";
import { Link } from "react-router-dom";
import Navbar from "./layout/Navbar";
import HeroSection from "./landing/HeroSection";
import FeatureSection from "./landing/FeatureSection";
import Footer from "./landing/Footer";
import AuthCard from "./auth/AuthCard";

const Home = () => {
  const [showAuthCard, setShowAuthCard] = useState(false);
  const [authTab, setAuthTab] = useState<"login" | "signup" | "recovery">(
    "login",
  );

  const handleLoginClick = () => {
    setAuthTab("login");
    setShowAuthCard(true);
  };

  const handleSignupClick = () => {
    setAuthTab("signup");
    setShowAuthCard(true);
  };

  const handleAuthClose = () => {
    setShowAuthCard(false);
  };

  const handleLoginSubmit = (values: any) => {
    console.log("Login submitted:", values);
    // In a real app, this would handle authentication and redirect to dashboard
    setShowAuthCard(false);
  };

  const handleSignupComplete = () => {
    console.log("Signup completed");
    // In a real app, this might show a success message or redirect
    setShowAuthCard(false);
  };

  const handleRecoveryComplete = (data: any) => {
    console.log("Recovery completed:", data);
    // In a real app, this might show a success message or redirect
    setShowAuthCard(false);
  };

  return (
    <div className="min-h-screen bg-white flex flex-col">
      {/* Navigation */}
      <Navbar
        onLoginClick={handleLoginClick}
        onSignupClick={handleSignupClick}
      />

      {/* Main Content */}
      <main className="flex-grow pt-20">
        {" "}
        {/* pt-20 to account for fixed navbar */}
        {/* Hero Section */}
        <HeroSection
          onPrimaryAction={handleSignupClick}
          onSecondaryAction={() => {
            const featuresSection = document.getElementById("features");
            if (featuresSection) {
              featuresSection.scrollIntoView({ behavior: "smooth" });
            }
          }}
        />
        {/* Features Section */}
        <div id="features">
          <FeatureSection />
        </div>
        {/* Authentication Modal */}
        {showAuthCard && (
          <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div className="relative w-full max-w-md">
              <button
                onClick={handleAuthClose}
                className="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors"
              >
                Close
              </button>
              <AuthCard
                defaultTab={authTab}
                onLoginSubmit={handleLoginSubmit}
                onSignupComplete={handleSignupComplete}
                onRecoveryComplete={handleRecoveryComplete}
              />
            </div>
          </div>
        )}
      </main>

      {/* Footer */}
      <Footer />
    </div>
  );
};

export default Home;
