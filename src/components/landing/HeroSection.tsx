import React from "react";
import { ArrowRight, TrendingUp, Shield, Wallet } from "lucide-react";
import { Button } from "@/components/ui/button";

interface HeroSectionProps {
  title?: string;
  subtitle?: string;
  ctaPrimaryText?: string;
  ctaSecondaryText?: string;
  onPrimaryAction?: () => void;
  onSecondaryAction?: () => void;
}

const HeroSection = ({
  title = "Next-Gen Crypto Trading Platform",
  subtitle = "Trade cryptocurrencies with confidence using our secure, fast, and intuitive platform. Real-time updates, advanced security, and seamless transactions.",
  ctaPrimaryText = "Get Started",
  ctaSecondaryText = "Learn More",
  onPrimaryAction = () => {},
  onSecondaryAction = () => {},
}: HeroSectionProps) => {
  return (
    <section className="w-full py-20 px-4 md:px-8 lg:px-16 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
      <div className="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-12">
        <div className="flex-1 space-y-8">
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight">
            {title}
          </h1>
          <p className="text-lg md:text-xl text-slate-300 max-w-2xl">
            {subtitle}
          </p>

          <div className="flex flex-wrap gap-4 pt-4">
            <Button
              size="lg"
              className="bg-primary hover:bg-primary/90 text-white font-medium"
              onClick={onPrimaryAction}
            >
              {ctaPrimaryText}
              <ArrowRight className="ml-2 h-5 w-5" />
            </Button>
            <Button
              variant="outline"
              size="lg"
              className="border-white text-white hover:bg-white/10"
              onClick={onSecondaryAction}
            >
              {ctaSecondaryText}
            </Button>
          </div>

          <div className="flex flex-wrap gap-8 pt-8">
            <div className="flex items-center gap-2">
              <div className="p-2 rounded-full bg-primary/20">
                <TrendingUp className="h-5 w-5 text-primary" />
              </div>
              <span className="text-sm md:text-base">Real-time Updates</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="p-2 rounded-full bg-primary/20">
                <Shield className="h-5 w-5 text-primary" />
              </div>
              <span className="text-sm md:text-base">Advanced Security</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="p-2 rounded-full bg-primary/20">
                <Wallet className="h-5 w-5 text-primary" />
              </div>
              <span className="text-sm md:text-base">
                Multi-currency Support
              </span>
            </div>
          </div>
        </div>

        <div className="flex-1 relative">
          <div className="relative z-10 bg-slate-800 p-4 rounded-xl shadow-2xl border border-slate-700 overflow-hidden">
            <img
              src="https://images.unsplash.com/photo-1639762681057-408e52192e55?w=800&q=80"
              alt="Crypto trading dashboard"
              className="rounded-lg w-full h-auto"
            />
            <div className="absolute top-0 left-0 w-full h-full bg-gradient-to-t from-slate-900/80 to-transparent rounded-lg flex items-end p-6">
              <div className="text-white">
                <p className="font-medium">Live Trading Dashboard</p>
                <p className="text-sm text-slate-300">
                  Real-time updates every 30 seconds
                </p>
              </div>
            </div>
          </div>

          <div className="absolute -top-6 -right-6 w-32 h-32 bg-primary/30 rounded-full blur-3xl"></div>
          <div className="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
